<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RestaurantOrder;
use Illuminate\Http\Request;

class AdminRestaurantOrderController extends Controller
{
    public function index()
    {
        $orders = RestaurantOrder::with(['guest.user', 'booking.room.roomType'])->latest()->paginate(15);

        return view('admin.restaurant-orders.index', compact('orders'));
    }

    public function show(RestaurantOrder $restaurantOrder)
    {
        $restaurantOrder->load(['guest.user', 'booking.room.roomType', 'details.menu']);

        return view('admin.restaurant-orders.show', compact('restaurantOrder'));
    }

    public function updateStatus(Request $request, RestaurantOrder $restaurantOrder)
    {
        $data = $request->validate([
            'order_status' => ['required', 'in:pending_payment,confirmed,cooking,ready,delivering,completed'],
        ]);

        $restaurantOrder->update($data);

        return redirect()->back()->with('status', 'Status pesanan restoran berhasil diperbarui.');
    }
}
