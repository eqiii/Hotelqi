<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRestaurantCartRequest;
use App\Http\Requests\StoreRestaurantCheckoutRequest;
use App\Models\Guest;
use App\Models\RestaurantMenu;
use App\Models\RestaurantOrder;
use App\Models\RestaurantOrderDetail;
use App\Services\MidtransService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class RestaurantController extends Controller
{
    private function ensureGuestAccess(): RedirectResponse|Guest|null
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        if ($user->role !== 'guest' || !$user->guest) {
            return redirect()->route('dashboard');
        }

        return $user->guest;
    }

    public function index()
    {
        $menus = RestaurantMenu::available()
            ->orderBy('category')
            ->get()
            ->groupBy('category');

        $guest = null;
        $bookings = collect();

        $user = Auth::user();
        if ($user && $user->role === 'guest' && $user->guest) {
            $guest = $user->guest;
            $bookings = $guest->bookings()->active()->get();
        }

        return view('restaurant.index', compact('menus', 'bookings'));
    }

    public function addToCart(StoreRestaurantCartRequest $request)
    {
        $guest = $this->ensureGuestAccess();
        if ($guest instanceof RedirectResponse) {
            return $guest;
        }

        $menu = RestaurantMenu::available()->findOrFail($request->menu_id);
        $cart = session('restaurant_cart', []);

        $cart[$menu->id] = [
            'menu_id' => $menu->id,
            'name' => $menu->name,
            'price' => (float) $menu->price,
            'quantity' => ($cart[$menu->id]['quantity'] ?? 0) + $request->quantity,
        ];

        session(['restaurant_cart' => $cart]);

        return response()->json(['success' => true, 'cart' => $cart]);
    }

    public function cart()
    {
        $guest = $this->ensureGuestAccess();
        if ($guest instanceof RedirectResponse) {
            return $guest;
        }

        $cart = session('restaurant_cart', []);
        $menus = RestaurantMenu::whereIn('id', array_column($cart, 'menu_id'))->get()->keyBy('id');

        return view('restaurant.cart', compact('cart', 'menus'));
    }

    public function updateCart(Request $request)
    {
        $guest = $this->ensureGuestAccess();
        if ($guest instanceof RedirectResponse) {
            return $guest;
        }

        $cart = session('restaurant_cart', []);
        foreach ($request->input('quantities', []) as $menuId => $quantity) {
            if (!isset($cart[$menuId])) {
                continue;
            }
            $cart[$menuId]['quantity'] = max(0, (int) $quantity);
            if ($cart[$menuId]['quantity'] === 0) {
                unset($cart[$menuId]);
            }
        }

        session(['restaurant_cart' => $cart]);

        return redirect()->back()->with('success', 'Keranjang diperbarui.');
    }

    public function removeFromCart(string $menuId)
    {
        $guest = $this->ensureGuestAccess();
        if ($guest instanceof RedirectResponse) {
            return $guest;
        }

        $cart = session('restaurant_cart', []);
        unset($cart[$menuId]);
        session(['restaurant_cart' => $cart]);

        return redirect()->back()->with('success', 'Item dihapus dari keranjang.');
    }

    public function checkout()
    {
        $guest = $this->ensureGuestAccess();
        if ($guest instanceof RedirectResponse) {
            return $guest;
        }

        $cart = session('restaurant_cart', []);
        if (empty($cart)) {
            return redirect()->route('restaurant')->withErrors(['cart' => 'Keranjang Anda kosong.']);
        }

        $guest = Auth::user()->guest;
        $activeBooking = $guest?->bookings()->active()->latest()->first();

        $menus = RestaurantMenu::whereIn('id', array_column($cart, 'menu_id'))->get()->keyBy('id');

        return view('restaurant.checkout', compact('cart', 'menus', 'activeBooking'));
    }

    public function storeCheckout(StoreRestaurantCheckoutRequest $request)
    {
        $guest = $this->ensureGuestAccess();
        if ($guest instanceof RedirectResponse) {
            return $guest;
        }

        $cart = session('restaurant_cart', []);
        if (empty($cart)) {
            return redirect()->route('restaurant')->withErrors(['cart' => 'Keranjang Anda kosong.']);
        }

        $booking = null;
        if ($request->dining_type === 'room_service' && $request->room_number) {
            $booking = $guest->bookings()->active()->whereHas('room', function ($query) use ($request) {
                $query->where('room_number', $request->room_number);
            })->first();
        }

        session(['restaurant_checkout' => [
            'cart' => $cart,
            'details' => [
                'serve_type' => $request->serve_type,
                'serve_time' => $request->serve_type === 'scheduled' ? $request->serve_time : null,
                'dining_type' => $request->dining_type,
                'guest_name' => $request->guest_name,
                'room_number' => $request->room_number,
                'notes' => $request->notes,
                'payment_method' => $request->payment_method,
                'booking_id' => $booking?->id,
            ],
        ]]);

        return redirect()->route('restaurant.payment');
    }

    public function payment()
    {
        $guest = $this->ensureGuestAccess();
        if ($guest instanceof RedirectResponse) {
            return $guest;
        }

        $checkout = session('restaurant_checkout');
        if (empty($checkout['cart'] ?? [])) {
            return redirect()->route('restaurant')->withErrors(['cart' => 'Checkout dibatalkan.']);
        }

        $subtotal = collect($checkout['cart'])->sum(fn($item) => (float) ($item['price'] ?? 0) * (int) ($item['quantity'] ?? 0));
        $tax = round($subtotal * 0.1, 2);
        $total = $subtotal + $tax;
        $guest = Auth::user()->guest;
        $activeBooking = $guest?->bookings()->active()->latest()->first();

        $snapToken = null;
        if (($checkout['details']['payment_method'] ?? 'midtrans') === 'midtrans') {
            $midtrans = new MidtransService();
            $orderId = 'restaurant-' . uniqid();
            try {
                $snapToken = $midtrans->createSnapToken($midtrans->buildSnapParams($orderId, (int) round($total), Auth::user()->name, Auth::user()->email));
                session()->put('restaurant_payment_order_id', $orderId);
            } catch (\Exception $e) {
                Log::error('Restaurant payment error: ' . $e->getMessage());
            }
        }

        return view('restaurant.payment', compact('checkout', 'subtotal', 'tax', 'total', 'snapToken', 'activeBooking'));
    }

    public function paymentFinish(Request $request)
    {
        $guest = $this->ensureGuestAccess();
        if ($guest instanceof RedirectResponse) {
            return $guest;
        }

        $orderId = $request->query('order_id');
        $status = $request->query('transaction_status');
        $checkout = session('restaurant_checkout');

        if (empty($checkout['cart'] ?? []) || empty($checkout['details'] ?? [])) {
            return redirect()->route('restaurant')->withErrors(['cart' => 'Data checkout tidak ditemukan.']);
        }

        if ($status !== 'settlement' && $status !== 'capture') {
            return redirect()->route('restaurant.orders')->withErrors(['payment' => 'Pembayaran belum selesai.']);
        }

        $guestId = $guest->id;

        $items = collect($checkout['cart']);
        $subtotal = $items->sum(fn($item) => (float) ($item['price'] ?? 0) * (int) ($item['quantity'] ?? 0));
        $tax = round($subtotal * 0.1, 2);
        $total = $subtotal + $tax;
        $bookingId = $checkout['details']['booking_id'] ?? null;
        $order = RestaurantOrder::create([
            'guest_id' => $guestId,
            'booking_id' => $bookingId,
            'invoice_number' => 'RST-' . strtoupper(uniqid()),
            'subtotal' => $subtotal,
            'tax' => $tax,
            'total' => $total,
            'total_price' => $total,
            'payment_method' => $checkout['details']['payment_method'] ?? 'midtrans',
            'payment_status' => 'paid',
            'order_status' => 'confirmed',
            'serve_type' => $checkout['details']['serve_type'] ?? 'now',
            'serve_time' => $checkout['details']['serve_time'] ?? null,
            'dining_type' => $checkout['details']['dining_type'] ?? 'dine_in',
            'guest_name' => $checkout['details']['guest_name'] ?? null,
            'room_number' => $checkout['details']['room_number'] ?? null,
            'notes' => $checkout['details']['notes'] ?? null,
            'paid_at' => now(),
            'status' => 'confirmed',
        ]);

        foreach ($items as $item) {
            $menu = RestaurantMenu::find($item['menu_id']);
            if (!$menu) {
                continue;
            }
            RestaurantOrderDetail::create([
                'restaurant_order_id' => $order->id,
                'restaurant_menu_id' => $menu->id,
                'quantity' => (int) ($item['quantity'] ?? 0),
                'price' => (float) ($item['price'] ?? 0),
                'subtotal' => (float) ($item['price'] ?? 0) * (int) ($item['quantity'] ?? 0),
            ]);
        }

        session()->forget(['restaurant_cart', 'restaurant_checkout', 'restaurant_payment_order_id']);

        return redirect()->route('user.restaurant.orders')->with('success', 'Pesanan restoran berhasil diproses.');
    }

    public function store(Request $request)
    {
        return $this->addToCart(new StoreRestaurantCartRequest($request->all()));
    }

    public function orders()
    {
        $guest = $this->ensureGuestAccess();
        if ($guest instanceof RedirectResponse) {
            return $guest;
        }

        $orders = $guest->restaurantOrders()
            ->with(['details.menu', 'booking.room.roomType'])
            ->latest()
            ->paginate(12);

        return view('user.restaurant.orders', compact('orders'));
    }

    public function show(RestaurantOrder $restaurantOrder)
    {
        $guest = $this->ensureGuestAccess();
        if ($guest instanceof RedirectResponse) {
            return $guest;
        }

        if ($restaurantOrder->guest_id !== $guest->id) {
            abort(403);
        }

        $restaurantOrder->load(['details.menu', 'booking.room.roomType']);

        return view('user.restaurant.show', compact('restaurantOrder'));
    }

    public function invoice(RestaurantOrder $restaurantOrder)
    {
        $guest = $this->ensureGuestAccess();
        if ($guest instanceof RedirectResponse) {
            return $guest;
        }

        if ($restaurantOrder->guest_id !== $guest->id) {
            abort(403);
        }

        $restaurantOrder->load(['details.menu', 'booking.room.roomType']);

        return view('user.restaurant.invoice', compact('restaurantOrder'));
    }
}
