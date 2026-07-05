<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class MidtransCallbackController extends Controller
{
    public function handle(Request $request): Response
    {
        try {
            $midtrans = new MidtransService();
            $notification = $midtrans->getNotification($request->all());

            $orderId = $notification->order_id ?? null;
            if (!$orderId) {
                Log::warning('Midtrans callback: Order ID tidak ditemukan', $request->all());
                return response('Order ID tidak ditemukan', 400);
            }

            $bookingId = (int) str_replace('booking-', '', $orderId);
            $booking = Booking::with('payment')->find($bookingId);

            if (!$booking || !$booking->payment) {
                Log::warning('Midtrans callback: Booking tidak ditemukan', ['order_id' => $orderId]);
                return response('Booking tidak ditemukan', 404);
            }

            $payment = $booking->payment;
            $transactionStatus = $notification->transaction_status ?? null;
            $fraudStatus = $notification->fraud_status ?? null;
            $transactionId = $notification->transaction_id ?? null;

            Log::info('Midtrans callback received', [
                'order_id'           => $orderId,
                'transaction_status' => $transactionStatus,
                'fraud_status'       => $fraudStatus,
            ]);

            // Tentukan status pembayaran berdasarkan status dari Midtrans
            if ($transactionStatus === 'capture') {
                if ($fraudStatus === 'challenge') {
                    $newPaymentStatus = 'unpaid'; // Masih menunggu review fraud
                } else {
                    $newPaymentStatus = 'paid';
                }
            } elseif ($transactionStatus === 'settlement') {
                $newPaymentStatus = 'paid';
            } elseif (in_array($transactionStatus, ['cancel', 'deny', 'expire'])) {
                $newPaymentStatus = 'failed';
            } elseif ($transactionStatus === 'pending') {
                $newPaymentStatus = 'unpaid';
            } else {
                $newPaymentStatus = $payment->payment_status;
            }

            $payment->update([
                'midtrans_transaction_id' => $transactionId,
                'payment_status'          => $newPaymentStatus,
            ]);

            // Jika pembayaran berhasil, mark as paid dan auto confirm
            if ($newPaymentStatus === 'paid' && !$payment->isPaid()) {
                $payment->markAsPaid();
            }

            return response('OK', 200);

        } catch (\Exception $e) {
            Log::error('Midtrans callback error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
            return response('Error: ' . $e->getMessage(), 500);
        }
    }
}
