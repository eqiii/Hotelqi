<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\Guest;
use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $totalRooms = Room::count();
        $availableRooms = Room::available()->count();
        $occupiedRooms = Room::occupied()->count();
        $maintenanceRooms = Room::maintenance()->count();

        $totalGuests = Guest::count();
        $activeBookings = Booking::active()->count();
        $totalRevenue = Payment::where('payment_status', 'paid')->sum('amount');

        $latestBookings = Booking::with('guest.user', 'room.roomType')->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalRooms',
            'availableRooms',
            'occupiedRooms',
            'maintenanceRooms',
            'totalGuests',
            'activeBookings',
            'totalRevenue',
            'latestBookings'
        ));
    }
}
