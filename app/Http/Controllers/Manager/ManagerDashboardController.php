<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\RestaurantMenu;
use App\Models\RestaurantOrder;
use App\Models\Room;
use App\Enums\PaymentStatus;
use Carbon\Carbon;

class ManagerDashboardController extends Controller
{
    public function index()
    {
        $totalRooms = Room::count();

        // ── Revenue ──────────────────────────────────────────────────
        $totalRevenue = Payment::where('payment_status', PaymentStatus::PAID)->sum('amount');

        $revenueThisMonth = Payment::where('payment_status', PaymentStatus::PAID)
            ->whereMonth('paid_at', now()->month)
            ->whereYear('paid_at', now()->year)
            ->sum('amount');

        // ── Bookings ─────────────────────────────────────────────────
        $totalBookings     = Booking::count();
        $completedBookings = Booking::where('status', 'checked_out')->count();

        // ── Occupancy Rate ────────────────────────────────────────────
        $occupiedRooms   = Booking::whereIn('status', ['confirmed', 'checked_in'])->count();
        $occupancyRate   = $totalRooms > 0
            ? round(($occupiedRooms / $totalRooms) * 100, 1)
            : 0;

        // ── Average Revenue per Booking ───────────────────────────────
        $paidBookingsCount   = Payment::where('payment_status', PaymentStatus::PAID)->count();
        $avgRevenuePerBooking = $paidBookingsCount > 0
            ? round($totalRevenue / $paidBookingsCount, 0)
            : 0;

        // ── Chart data: Revenue per Month (current year) ──────────────
        $revenuePerMonth = [];
        for ($m = 1; $m <= 12; $m++) {
            $revenuePerMonth[] = (float) Payment::where('payment_status', PaymentStatus::PAID)
                ->whereYear('paid_at', now()->year)
                ->whereMonth('paid_at', $m)
                ->sum('amount');
        }

        // ── Chart data: Bookings per Month (current year) ─────────────
        $bookingsPerMonth = [];
        for ($m = 1; $m <= 12; $m++) {
            $bookingsPerMonth[] = Booking::whereYear('created_at', now()->year)
                ->whereMonth('created_at', $m)
                ->count();
        }

        $restaurantRevenueToday = RestaurantOrder::where('payment_status', 'paid')
            ->whereDate('paid_at', today())
            ->sum('total_price');
        $restaurantRevenueThisMonth = RestaurantOrder::where('payment_status', 'paid')
            ->whereMonth('paid_at', now()->month)
            ->whereYear('paid_at', now()->year)
            ->sum('total_price');
        $restaurantTotalOrders = RestaurantOrder::count();
        $restaurantAverageOrderValue = $restaurantTotalOrders > 0
            ? RestaurantOrder::where('payment_status', 'paid')->avg('total_price')
            : 0;
        $bestSellingMenu = RestaurantOrder::query()
            ->join('restaurant_order_details', 'restaurant_orders.id', '=', 'restaurant_order_details.restaurant_order_id')
            ->join('restaurant_menus', 'restaurant_order_details.restaurant_menu_id', '=', 'restaurant_menus.id')
            ->selectRaw('restaurant_menus.name, SUM(restaurant_order_details.quantity) as total_quantity')
            ->groupBy('restaurant_menus.id', 'restaurant_menus.name')
            ->orderByDesc('total_quantity')
            ->first();

        return view('manager.dashboard', compact(
            'totalRevenue',
            'revenueThisMonth',
            'totalBookings',
            'completedBookings',
            'occupancyRate',
            'avgRevenuePerBooking',
            'revenuePerMonth',
            'bookingsPerMonth',
            'restaurantRevenueToday',
            'restaurantRevenueThisMonth',
            'restaurantTotalOrders',
            'restaurantAverageOrderValue',
            'bestSellingMenu'
        ));
    }
}
