<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRestaurantCartRequest;
use App\Http\Requests\StoreRestaurantCheckoutRequest;
use App\Models\Guest;
use App\Models\RestaurantMenu;
use App\Models\RestaurantOrder;
use App\Models\RestaurantOrderDetail;
use App\Services\MidtransService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RestaurantController extends Controller
{
    // ==================== HELPERS ====================

    /**
     * Ensure the current user is an authenticated guest.
     *
     * For AJAX/JSON requests a JSON response is returned on failure so the
     * caller can handle it gracefully without a page redirect.
     *
     * @return Guest|RedirectResponse|JsonResponse
     */
    private function ensureGuestAccess(): Guest|RedirectResponse|JsonResponse
    {
        $user = Auth::user();

        if (!$user) {
            if (request()->expectsJson()) {
                return response()->json(['error' => 'Unauthenticated', 'redirect' => route('login')], 401);
            }
            return redirect()->route('login');
        }

        if ($user->role !== 'guest' || !$user->guest) {
            if (request()->expectsJson()) {
                return response()->json(['error' => 'Forbidden'], 403);
            }
            return redirect()->route('dashboard');
        }

        return $user->guest;
    }

    // ==================== PUBLIC MENU ====================

    public function index(Request $request)
    {
        $query = RestaurantMenu::available();

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $menus = $query->orderBy('category')
            ->get()
            ->groupBy('category');

        $guest    = null;
        $bookings = collect();

        $user = Auth::user();
        if ($user && $user->role === 'guest' && $user->guest) {
            $guest    = $user->guest;
            $bookings = $guest->bookings()->active()->get();
        }

        $categories = RestaurantMenu::CATEGORIES;
        $selectedCategory = $request->category;

        return view('restaurant.index', compact('menus', 'bookings', 'categories', 'selectedCategory'));
    }

    // ==================== CART ====================

    /**
     * Add a menu item to the session cart (supports AJAX).
     */
    public function addToCart(StoreRestaurantCartRequest $request): JsonResponse|RedirectResponse
    {
        $guest = $this->ensureGuestAccess();

        // If ensureGuestAccess() returned a response (redirect or JSON error)
        // forward it immediately.
        if ($guest instanceof RedirectResponse || $guest instanceof JsonResponse) {
            return $guest;
        }

        $menu = RestaurantMenu::available()->findOrFail($request->menu_id);
        $cart = session('restaurant_cart', []);

        // Validate stock if the menu has a limited stock
        if ($menu->stock_quantity !== null && $menu->stock_quantity < 1) {
            return response()->json(['success' => false, 'message' => 'Stok menu habis.'], 422);
        }

        $currentQty = $cart[$menu->id]['quantity'] ?? 0;
        $newQty     = $currentQty + (int) $request->quantity;

        // Enforce stock ceiling if stock is tracked
        if ($menu->stock_quantity !== null && $newQty > $menu->stock_quantity) {
            return response()->json([
                'success' => false,
                'message' => 'Jumlah melebihi stok yang tersedia (' . $menu->stock_quantity . ').',
            ], 422);
        }

        $cart[$menu->id] = [
            'menu_id'  => $menu->id,
            'name'     => $menu->name,
            'price'    => (float) $menu->price,
            'quantity' => $newQty,
        ];

        session(['restaurant_cart' => $cart]);

        return response()->json([
            'success'     => true,
            'cart'        => $cart,
            'cart_count'  => array_sum(array_column($cart, 'quantity')),
        ]);
    }

    public function cart()
    {
        $guest = $this->ensureGuestAccess();
        if ($guest instanceof RedirectResponse || $guest instanceof JsonResponse) {
            return $guest instanceof JsonResponse
                ? redirect()->route('login')
                : $guest;
        }

        $cart  = session('restaurant_cart', []);
        $menus = RestaurantMenu::whereIn('id', array_column($cart, 'menu_id'))->get()->keyBy('id');

        return view('restaurant.cart', compact('cart', 'menus'));
    }

    /**
     * Update cart quantities.
     *
     * Supports:
     * - Legacy form bulk update via `quantities[menuId]`
     * - AJAX single-item update via `menu_id` + `quantity`
     */
    public function updateCart(Request $request): JsonResponse|RedirectResponse
    {
        $guest = $this->ensureGuestAccess();
        if ($guest instanceof RedirectResponse || $guest instanceof JsonResponse) {
            return $request->expectsJson()
                ? response()->json(['error' => 'Unauthenticated'], 401)
                : redirect()->route('login');
        }

        $cart = session('restaurant_cart', []);

        // AJAX single-item update (used by +/- buttons in sidebar)
        if ($request->expectsJson()) {
            $menuId = $request->input('menu_id');

            if ($menuId && isset($cart[$menuId])) {
                $quantity = (int) $request->input('quantity', 0);

                if ($quantity <= 0) {
                    unset($cart[$menuId]);
                } else {
                    $cart[$menuId]['quantity'] = $quantity;
                }
            }

            session(['restaurant_cart' => $cart]);

            return response()->json([
                'success'    => true,
                'cart'       => $cart,
                'cart_count' => array_sum(array_column($cart, 'quantity')),
            ]);
        }

        // Legacy form bulk update
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

    /**
     * Remove an item from the cart.
     * Returns JSON for AJAX requests, redirect for normal requests.
     */
    public function removeFromCart(string $menuId, Request $request): JsonResponse|RedirectResponse
    {
        $guest = $this->ensureGuestAccess();
        if ($guest instanceof RedirectResponse || $guest instanceof JsonResponse) {
            return $request->expectsJson()
                ? response()->json(['error' => 'Unauthenticated'], 401)
                : redirect()->route('login');
        }

        $cart = session('restaurant_cart', []);
        unset($cart[$menuId]);
        session(['restaurant_cart' => $cart]);

        if ($request->expectsJson()) {
            return response()->json([
                'success'    => true,
                'cart'       => $cart,
                'cart_count' => array_sum(array_column($cart, 'quantity')),
            ]);
        }

        return redirect()->back()->with('success', 'Item dihapus dari keranjang.');
    }

    // ==================== CHECKOUT ====================

    public function checkout()
    {
        $guest = $this->ensureGuestAccess();
        if ($guest instanceof RedirectResponse || $guest instanceof JsonResponse) {
            return redirect()->route('login');
        }

        $cart = session('restaurant_cart', []);
        if (empty($cart)) {
            return redirect()->route('restaurant')->withErrors(['cart' => 'Keranjang Anda kosong.']);
        }

        $activeBooking = $guest->bookings()->active()->latest()->first();
        $menus         = RestaurantMenu::whereIn('id', array_column($cart, 'menu_id'))->get()->keyBy('id');

        return view('restaurant.checkout', compact('cart', 'menus', 'activeBooking'));
    }

    /**
     * Save checkout details to session and forward to payment page.
     */
    public function storeCheckout(StoreRestaurantCheckoutRequest $request): RedirectResponse
    {
        $guest = $this->ensureGuestAccess();
        if ($guest instanceof RedirectResponse || $guest instanceof JsonResponse) {
            return redirect()->route('login');
        }

        $cart = session('restaurant_cart', []);
        if (empty($cart)) {
            return redirect()->route('restaurant')->withErrors(['cart' => 'Keranjang Anda kosong.']);
        }

        // Resolve booking_id from the validated room_number (room_service only).
        $bookingId = null;
        if ($request->dining_type === 'room_service' && $request->room_number) {
            $booking = $guest->bookings()
                ->active()
                ->whereHas('room', fn($q) => $q->where('room_number', $request->room_number))
                ->first();

            $bookingId = $booking?->id;
        }

        session(['restaurant_checkout' => [
            'cart'    => $cart,
            'details' => [
                'serve_type'     => $request->serve_type,
                'serve_time'     => $request->serve_type === 'scheduled' ? $request->serve_time : null,
                'dining_type'    => $request->dining_type,
                'guest_name'     => $request->dining_type === 'dine_in' ? $request->guest_name : null,
                'room_number'    => $request->dining_type === 'room_service' ? $request->room_number : null,
                'notes'          => $request->notes,
                'payment_method' => $request->payment_method,
                'booking_id'     => $bookingId,
            ],
        ]]);

        return redirect()->route('user.restaurant.payment');
    }

    // ==================== PAYMENT ====================

    public function payment()
    {
        $guest = $this->ensureGuestAccess();
        if ($guest instanceof RedirectResponse || $guest instanceof JsonResponse) {
            return redirect()->route('login');
        }

        $checkout = session('restaurant_checkout');
        if (empty($checkout['cart'] ?? [])) {
            return redirect()->route('restaurant')->withErrors(['cart' => 'Checkout dibatalkan.']);
        }

        $subtotal      = collect($checkout['cart'])->sum(fn($item) => (float) ($item['price'] ?? 0) * (int) ($item['quantity'] ?? 0));
        $tax           = round($subtotal * 0.1, 2);
        $total         = $subtotal + $tax;
        $activeBooking = $guest->bookings()->active()->latest()->first();

        $snapToken = null;
        if (($checkout['details']['payment_method'] ?? 'midtrans') === 'midtrans') {
            $midtrans = new MidtransService();
            $orderId  = 'restaurant-' . uniqid();

            try {
                // Pass the restaurant-specific finish URL so Midtrans redirects
                // back here, NOT to the Booking finish page.
                $finishUrl = route('user.restaurant.payment.finish');

                $snapToken = $midtrans->createSnapToken(
                    $midtrans->buildSnapParams(
                        $orderId,
                        (int) round($total),
                        Auth::user()->name,
                        Auth::user()->email,
                        $finishUrl      // ← restaurant-specific finish URL
                    )
                );

                // Persist a pending RestaurantOrder keyed by Midtrans order_id
                // so history can show immediately after payment succeeds.
                DB::transaction(function () use ($guest, $checkout, $orderId) {
                    $items = collect($checkout['cart'] ?? []);

                    $bookingId = $checkout['details']['booking_id'] ?? null;

                    // If order already created (retry), don't duplicate.
                    $order = RestaurantOrder::query()
                        ->where('midtrans_order_id', $orderId)
                        ->first();

                    if (!$order) {
                        $user = Auth::user();
                        $order = RestaurantOrder::create([
                            'user_id' => $user->id,
                            'guest_id' => $guest->id,
                            'booking_id' => $bookingId,
                            'order_number' => RestaurantOrder::generateOrderNumber(),
                            'invoice_number' => 'RST-' . strtoupper(uniqid()),
                            'subtotal' => $subtotal = $items->sum(fn($i) => (float)($i['price'] ?? 0) * (int)($i['quantity'] ?? 0)),
                            'tax' => $tax = round($subtotal * 0.1, 2),
                            'total' => $subtotal + $tax,
                            'total_price' => $subtotal + $tax,
                            'payment_method' => 'midtrans',
                            'payment_status' => 'pending',
                            'order_status' => 'pending',
                            'serve_type' => $checkout['details']['serve_type'] ?? 'now',
                            'serve_time' => $checkout['details']['serve_time'] ?? null,
                            'dining_type' => $checkout['details']['dining_type'] ?? 'dine_in',
                            'guest_name' => $checkout['details']['guest_name'] ?? null,
                            'room_number' => $checkout['details']['room_number'] ?? null,
                            'notes' => $checkout['details']['notes'] ?? null,
                            'paid_at' => null,
                            'status' => 'pending',
                            'midtrans_order_id' => $orderId,
                        ]);

                        foreach ($items as $item) {
                            $menu = RestaurantMenu::lockForUpdate()->find($item['menu_id']);
                            if (!$menu) {
                                continue;
                            }

                            $qty = (int)($item['quantity'] ?? 0);

                            RestaurantOrderDetail::create([
                                'restaurant_order_id' => $order->id,
                                'restaurant_menu_id' => $menu->id,
                                'quantity' => $qty,
                                'price' => (float)($item['price'] ?? 0),
                                'subtotal' => (float)($item['price'] ?? 0) * $qty,
                            ]);
                        }
                    }
                });

                session()->put('restaurant_payment_order_id', $orderId);
            } catch (\Exception $e) {
                Log::error('Restaurant payment error: ' . $e->getMessage());
            }
        }

        return view('restaurant.payment', compact(
            'checkout',
            'subtotal',
            'tax',
            'total',
            'snapToken',
            'activeBooking'
        ));
    }

    /**
     * Called after Midtrans redirects back (or after manual-transfer confirmation).
     *
     * Only creates the order + items AFTER payment is confirmed to avoid
     * orphaned records. Everything is wrapped in a DB transaction so partial
     * failures roll back cleanly.
     */
    public function paymentFinish(Request $request): RedirectResponse
    {
        $guest = $this->ensureGuestAccess();
        if ($guest instanceof RedirectResponse || $guest instanceof JsonResponse) {
            return redirect()->route('login');
        }

        $orderId  = $request->query('order_id') ?? session('restaurant_payment_order_id');
        $status   = $request->query('transaction_status');
        $checkout = session('restaurant_checkout');

        // Session checkout may be missing when user returns from Midtrans callback.
        // Order persistence is handled using midtrans_order_id.
        if (empty($orderId)) {
            return redirect()->route('restaurant')->withErrors(['cart' => 'Data checkout tidak ditemukan.']);
        }

        try {
            DB::transaction(function () use ($guest, $checkout, $orderId, $status) {
                $order = RestaurantOrder::query()
                    ->where('midtrans_order_id', $orderId)
                    ->first();

                if (!$order) {
                    // Safety net: order was not created during payment().
                    // Try to create it from session data or restaurant_payment_order_id.
                    if (empty($checkout['cart'] ?? []) || empty($checkout['details'] ?? [])) {
                        throw new \RuntimeException('Restaurant order not found for midtrans_order_id');
                    }

                    $items = collect($checkout['cart']);
                    $subtotal = $items->sum(fn($item) => (float)($item['price'] ?? 0) * (int)($item['quantity'] ?? 0));
                    $tax = round($subtotal * 0.1, 2);
                    $total = $subtotal + $tax;
                    $bookingId = $checkout['details']['booking_id'] ?? null;

                    $order = RestaurantOrder::create([
                        'user_id' => $guest->user_id,
                        'guest_id' => $guest->id,
                        'booking_id' => $bookingId,
                        'order_number' => RestaurantOrder::generateOrderNumber(),
                        'invoice_number' => 'RST-' . strtoupper(uniqid()),
                        'subtotal' => $subtotal,
                        'tax' => $tax,
                        'total' => $total,
                        'total_price' => $total,
                        'payment_method' => $checkout['details']['payment_method'] ?? 'midtrans',
                        'payment_status' => 'pending',
                        'order_status' => 'pending',
                        'serve_type' => $checkout['details']['serve_type'] ?? 'now',
                        'serve_time' => $checkout['details']['serve_time'] ?? null,
                        'dining_type' => $checkout['details']['dining_type'] ?? 'dine_in',
                        'guest_name' => $checkout['details']['guest_name'] ?? null,
                        'room_number' => $checkout['details']['room_number'] ?? null,
                        'notes' => $checkout['details']['notes'] ?? null,
                        'paid_at' => null,
                        'status' => 'pending',
                        'midtrans_order_id' => $orderId,
                    ]);

                    foreach ($items as $item) {
                        $menu = RestaurantMenu::lockForUpdate()->find($item['menu_id']);
                        if (!$menu) {
                            continue;
                        }

                        $qty = (int)($item['quantity'] ?? 0);

                        RestaurantOrderDetail::create([
                            'restaurant_order_id' => $order->id,
                            'restaurant_menu_id' => $menu->id,
                            'quantity' => $qty,
                            'price' => (float)($item['price'] ?? 0),
                            'subtotal' => (float)($item['price'] ?? 0) * $qty,
                        ]);
                    }
                }

                // Determine if payment is actually settled/captured
                $isPaid = in_array($status, ['settlement', 'capture'], true);

                // Only update if currently pending (avoid overwriting callback updates)
                if ($order->payment_status === 'pending' || $isPaid) {
                    $order->update([
                        'payment_status' => $isPaid ? 'paid' : 'pending',
                        'order_status' => $isPaid ? 'confirmed' : 'pending',
                        'paid_at' => $isPaid ? now() : $order->paid_at,
                    ]);
                }

                // Decrement stock only when first time becoming paid
                if ($isPaid) {
                    $order->loadMissing(['details']);
                    foreach ($order->details as $detail) {
                        $menu = RestaurantMenu::lockForUpdate()->find($detail->restaurant_menu_id);
                        if ($menu && $menu->stock_quantity !== null) {
                            $menu->decrement('stock_quantity', (int)$detail->quantity);
                        }
                    }
                }
            });
        } catch (\Throwable $e) {
            Log::error('Restaurant order creation failed: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->route('user.restaurant.orders')
                ->withErrors(['payment' => 'Terjadi kesalahan saat menyimpan pesanan. Hubungi staf hotel.']);
        }

        // 4. Clear session data only after the transaction succeeds.
        session()->forget(['restaurant_cart', 'restaurant_checkout', 'restaurant_payment_order_id']);

        return redirect()->route('user.restaurant.orders')
            ->with('success', 'Pesanan restoran berhasil diproses! Terima kasih.');
    }

    // ==================== ORDER HISTORY ====================

    public function orders()
    {
        $guest = $this->ensureGuestAccess();
        if ($guest instanceof RedirectResponse || $guest instanceof JsonResponse) {
            return redirect()->route('login');
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
        if ($guest instanceof RedirectResponse || $guest instanceof JsonResponse) {
            return redirect()->route('login');
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
        if ($guest instanceof RedirectResponse || $guest instanceof JsonResponse) {
            return redirect()->route('login');
        }

        if ($restaurantOrder->guest_id !== $guest->id) {
            abort(403);
        }

        $restaurantOrder->load(['details.menu', 'booking.room.roomType']);

        return view('user.restaurant.invoice', compact('restaurantOrder'));
    }
}
