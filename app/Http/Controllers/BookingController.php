<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookingRequest;
use App\Models\Booking;
use App\Models\Room;
use App\Models\RoomType;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function store(StoreBookingRequest $request)
    {
        $roomType = RoomType::findOrFail($request->room_type_id);

        $room = Room::where('room_type_id', $request->room_type_id)
            ->availableBetween($request->check_in, $request->check_out)
            ->first();

        if (!$room) {
            return redirect()->back()->withErrors([
                'check_in' => 'Maaf, kamar untuk tipe ini tidak tersedia pada tanggal tersebut.'
            ])->withInput();
        }

        $priceCalc = $roomType->calculateTotalPrice($request->check_in, $request->check_out);

        $guest = auth()->user()->guest;

        $booking = Booking::create([
            'guest_id' => $guest->id,
            'room_id' => $room->id,
            'check_in' => $request->check_in,
            'check_out' => $request->check_out,
            'total_nights' => $priceCalc['total_nights'],
            'total_price' => $priceCalc['total_price'],
            'status' => 'pending',
            'notes' => $request->notes,
        ]);

        $booking->histories()->create([
            'status' => 'pending',
            'notes' => 'Booking dibuat oleh tamu.',
            'changed_by' => auth()->id(),
        ]);

        $booking->payment()->create([
            'amount' => $priceCalc['total_price'],
            'payment_method' => 'manual',
            'payment_status' => 'unpaid',
        ]);

        return redirect()->route('user.booking.payment', $booking);
    }

    public function payment(Booking $booking)
    {
        if ($booking->guest_id !== auth()->user()->guest->id) {
            abort(403);
        }

        $booking->load('room.roomType', 'payment');

        return view('user.payment', compact('booking'));
    }

    public function history()
    {
        $bookings = auth()->user()->guest->bookings()
            ->with(['room.roomType', 'payment'])
            ->latest()
            ->paginate(10);

        return view('user.history', compact('bookings'));
    }
}
