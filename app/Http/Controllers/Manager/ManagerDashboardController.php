<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Payment;
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

        return view('manager.dashboard', compact(
            'totalRevenue',
            'revenueThisMonth',
            'totalBookings',
            'completedBookings',
            'occupancyRate',
            'avgRevenuePerBooking',
            'revenuePerMonth',
            'bookingsPerMonth'
        ));
    }
}
