<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class MidtransCallbackController extends Controller
{
    public function handle(Request $request): Response
    {
        $midtrans = new MidtransService();
        $notification = $midtrans->getNotification($request->all());

        $orderId = $notification->order_id ?? null;
        if (!$orderId) {
            return response('Order ID tidak ditemukan', 400);
        }

        $bookingId = (int) str_replace('booking-', '', $orderId);
        $booking = Booking::with('payment')->find($bookingId);

        if (!$booking || !$booking->payment) {
            return response('Booking tidak ditemukan', 404);
        }

        $payment = $booking->payment;
        $status = $notification->transaction_status ?? null;
        $transactionId = $notification->transaction_id ?? null;

        $payment->update([
            'midtrans_transaction_id' => $transactionId,
            'payment_status' => match ($status) {
                'settlement', 'capture' => 'paid',
                'cancel', 'deny', 'expire' => 'failed',
                default => $payment->payment_status,
            },
        ]);

        if (in_array($status, ['settlement', 'capture'], true)) {
            $payment->markAsPaid();
        }

        return response('OK', 200);
    }
}
