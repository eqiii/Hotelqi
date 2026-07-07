<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookingRequest;
use App\Models\Booking;
use App\Models\Room;
use App\Models\RoomType;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function store(StoreBookingRequest $request)
    {
        $user = Auth::user();
        // Ensure room type exists
        $roomType = RoomType::findOrFail($request->room_type_id);

        // Update or create guest data from form (phone, address, ktp_number, avatar)
        $guest = $user->guest;

        $guestData = [
            'full_name' => $request->full_name,
            'phone' => $request->phone,
            'address' => $request->address,
            'ktp_number' => $request->ktp_number,
        ];

        if ($request->hasFile('avatar')) {
            $guestData['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        if ($guest) {
            $guest->update($guestData);
        } else {
            $guest = $user->guest()->create($guestData + ['user_id' => $user->id]);
        }

        $room = Room::where('room_type_id', $request->room_type_id)
            ->availableBetween($request->check_in, $request->check_out)
            ->first();

        if (!$room) {
            return redirect()->back()->withErrors([
                'check_in' => 'Maaf, kamar untuk tipe ini tidak tersedia pada tanggal tersebut. Silakan pilih tanggal lain.',
            ])->withInput();
        }

        $priceCalc = $roomType->calculateTotalPrice($request->check_in, $request->check_out);

        $booking = Booking::create([
            'guest_id'     => $guest->id,
            'room_id'      => $room->id,
            'check_in'     => $request->check_in,
            'check_out'    => $request->check_out,
            'total_nights' => $priceCalc['total_nights'],
            'total_price'  => $priceCalc['total_price'],
            'status'       => 'pending',
            'notes'        => $request->notes,
        ]);

        $booking->histories()->create([
            'status'     => 'pending',
            'notes'      => 'Booking dibuat oleh tamu.',
            'changed_by' => Auth::id(),
        ]);

        $booking->payment()->create([
            'amount'         => $priceCalc['total_price'],
            'payment_method' => 'midtrans',
            'payment_status' => \App\Enums\PaymentStatus::PENDING,
        ]);

        return redirect()->route('user.booking.payment', $booking)
            ->with('success', 'Booking berhasil dibuat! Silakan selesaikan pembayaran.');
    }

    public function create(RoomType $roomType)
    {
        // Render the combined guest + booking form. Guest can be created/updated on submit.
        return view('user.booking.create', compact('roomType'));
    }

    public function payment(Booking $booking)
    {
        // Pastikan booking milik user yang login (safely check guest)
        $guestId = Auth::user()->guest?->id;
        if ($booking->guest_id !== $guestId) {
            abort(403, 'Anda tidak berhak mengakses halaman ini.');
        }

        $booking->load('room.roomType', 'payment');

        $snapToken = null;

        // Generate snap token jika payment belum lunas
        if ($booking->payment && $booking->payment->payment_method === 'midtrans' && !$booking->payment->isPaid()) {
            try {
                $midtrans = new MidtransService();
                $orderId = 'booking-' . $booking->id;

                // Gunakan snap token yang sudah ada jika masih valid
                if ($booking->payment->midtrans_snap_token) {
                    $snapToken = $booking->payment->midtrans_snap_token;
                } else {
                    $snapToken = $midtrans->createSnapToken(
                        $midtrans->buildSnapParams(
                            $orderId,
                            (int) $booking->total_price,
                            Auth::user()->name,
                            Auth::user()->email
                        )
                    );

                    $booking->payment->update([
                        'midtrans_snap_token' => $snapToken,
                    ]);
                }
            } catch (\Exception $e) {
                // Log error tapi jangan crash
                \Log::error('Midtrans error: ' . $e->getMessage());
                $snapToken = null;
            }
        }

        return view('user.payment', compact('booking', 'snapToken'));
    }

    public function finishPayment(Request $request)
    {
        $orderId = $request->query('order_id');
        $status = $request->query('transaction_status');
        $booking = null;

        if ($orderId) {
            $bookingId = (int) str_replace('booking-', '', $orderId);
            $booking = Booking::with(['room.roomType', 'payment', 'guest.user'])
                ->where('id', $bookingId)
                ->where('guest_id', Auth::check() ? Auth::user()->guest?->id : null)
                ->first();
        }

        return view('user.payment-finish', [
            'orderId'  => $orderId,
            'status'   => $status,
            'booking'  => $booking,
        ]);
    }

    public function history()
    {
        $guest = Auth::user()->guest;
        if (!$guest) {
            return redirect()->route('profile.edit')->withErrors(['general' => 'Silakan lengkapi data tamu Anda terlebih dahulu.']);
        }

        $bookings = $guest->bookings()
            ->with(['room.roomType', 'payment'])
            ->latest()
            ->paginate(10);

        return view('user.history', compact('bookings'));
    }
}
