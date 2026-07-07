<?php

namespace App\Http\Controllers;

use App\Models\RestaurantMenu;
use App\Models\RestaurantOrder;
use App\Models\RestaurantOrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RestaurantController extends Controller
{
    public function index()
    {
        $menus = RestaurantMenu::available()
            ->orderBy('category')
            ->get()
            ->groupBy('category');

        $bookings = Auth::check() ? Auth::user()->guest->bookings()->active()->get() : collect();

        return view('restaurant.index', compact('menus', 'bookings'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'menu_id' => ['required', 'array'],
            'menu_id.*' => ['required', 'integer', 'exists:restaurant_menus,id'],
            'quantity' => ['required', 'array'],
            'quantity.*' => ['required', 'integer', 'min:1'],
            'booking_id' => ['nullable', 'integer', 'exists:bookings,id'],
        ]);

        $guest = Auth::user()->guest;

        $selectedMenus = RestaurantMenu::available()
            ->whereIn('id', $data['menu_id'])
            ->get()
            ->keyBy('id');

        if ($selectedMenus->isEmpty()) {
            return redirect()->back()->withErrors(['menu_id' => 'Silakan pilih minimal satu menu yang tersedia.']);
        }

        $bookingId = null;
        if (!empty($data['booking_id'])) {
            $booking = $guest->bookings()->where('id', $data['booking_id'])->first();
            if ($booking) {
                $bookingId = $booking->id;
            }
        }

        $order = RestaurantOrder::create([
            'guest_id' => $guest->id,
            'booking_id' => $bookingId,
            'total_price' => 0,
            'status' => 'pending',
        ]);

        foreach ($data['menu_id'] as $index => $menuId) {
            $quantity = (int) ($data['quantity'][$index] ?? 0);
            if ($quantity <= 0 || !isset($selectedMenus[$menuId])) {
                continue;
            }

            $menu = $selectedMenus[$menuId];
            RestaurantOrderDetail::create([
                'restaurant_order_id' => $order->id,
                'restaurant_menu_id' => $menu->id,
                'quantity' => $quantity,
                'price' => $menu->price,
            ]);
        }

        if ($order->details()->count() === 0) {
            $order->delete();
            return redirect()->back()->withErrors(['menu_id' => 'Silakan pilih menu dan jumlah yang valid.']);
        }

        $order->recalculateTotal();

        return redirect()->route('restaurant.orders')->with('success', 'Pesanan restoran berhasil dibuat. Silakan cek status pesanan Anda.');
    }

    public function orders()
    {
        $orders = Auth::user()->guest->restaurantOrders()
            ->with(['details.menu', 'booking.room.roomType'])
            ->latest()
            ->paginate(12);

        return view('user.restaurant.orders', compact('orders'));
    }

    public function show(RestaurantOrder $restaurantOrder)
    {
        if ($restaurantOrder->guest_id !== Auth::user()->guest->id) {
            abort(403);
        }

        $restaurantOrder->load(['details.menu', 'booking.room.roomType']);

        return view('user.restaurant.show', compact('restaurantOrder'));
    }
}
