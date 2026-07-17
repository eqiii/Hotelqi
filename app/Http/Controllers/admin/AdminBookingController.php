<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class AdminBookingController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->status;

        $query = Booking::with(['guest.user', 'room.roomType', 'payment']);

        if ($status) {
            $query->where('status', $status);
        }

        $bookings = $query->latest()->paginate(15);

        return view('admin.bookings.index', compact('bookings'));
    }

    public function show(Booking $booking)
    {
        $booking->load(['guest.user', 'room.roomType', 'payment', 'histories']);
        return view('admin.bookings.show', compact('booking'));
    }

    public function confirm(Booking $booking)
    {
        $booking->updateStatus('confirmed', 'Booking dikonfirmasi oleh Admin.', auth()->id());

        if ($booking->payment && !$booking->payment->isPaid()) {
            $booking->payment->markAsPaid();
        }

        return redirect()->route('admin.bookings.index')->with('success', 'Booking berhasil dikonfirmasi.');
    }

    public function checkIn(Booking $booking)
    {
        $booking->updateStatus('checked_in', 'Tamu telah check-in.', auth()->id());
        $booking->room->update(['status' => 'occupied']);

        return redirect()->route('admin.bookings.index')->with('success', 'Check-in berhasil.');
    }

    public function checkOut(Booking $booking)
    {
        $booking->updateStatus('checked_out', 'Tamu telah check-out.', auth()->id());
        $booking->room->update(['status' => 'available']);

        return redirect()->route('admin.bookings.index')->with('success', 'Check-out berhasil.');
    }

    public function cancel(Booking $booking)
    {
        $booking->updateStatus('cancelled', 'Booking dibatalkan oleh Admin.', auth()->id());

        if ($booking->room && $booking->room->status === 'occupied') {
            $booking->room->update(['status' => 'available']);
        }

        return redirect()->route('admin.bookings.index')->with('success', 'Booking berhasil dibatalkan.');
    }
}
