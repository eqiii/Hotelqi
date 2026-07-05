<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
use App\Services\MidtransService;
use App\Enums\PaymentStatus;
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

            // remember prior paid state to decide whether to call markAsPaid()
            $wasPaid = $payment->isPaid();

            Log::info('Midtrans callback received', [
                'order_id'           => $orderId,
                'transaction_status' => $transactionStatus,
                'fraud_status'       => $fraudStatus,
            ]);

            // Tentukan status pembayaran berdasarkan status dari Midtrans
            // Map Midtrans statuses to local payment_status values (canonical)
            if ($transactionStatus === 'capture') {
                // capture (card) -> paid unless fraud challenge
                if ($fraudStatus === 'challenge') {
                    $newPaymentStatus = PaymentStatus::PENDING; // still pending review
                } else {
                    $newPaymentStatus = PaymentStatus::PAID;
                }
            } elseif ($transactionStatus === 'settlement') {
                $newPaymentStatus = PaymentStatus::PAID;
            } elseif ($transactionStatus === 'pending') {
                $newPaymentStatus = PaymentStatus::PENDING;
            } elseif ($transactionStatus === 'expire') {
                $newPaymentStatus = PaymentStatus::EXPIRED;
            } elseif (in_array($transactionStatus, ['cancel', 'failure', 'deny'], true)) {
                // Map cancel/failure/deny to 'cancelled' or 'failed' depending on semantics; choose CANCELLED for cancel/deny, FAILED for failure
                if ($transactionStatus === 'cancel' || $transactionStatus === 'deny') {
                    $newPaymentStatus = PaymentStatus::CANCELLED;
                } else {
                    $newPaymentStatus = PaymentStatus::FAILED;
                }
            } else {
                $newPaymentStatus = $payment->payment_status;
            }

            $payment->update([
                'midtrans_transaction_id' => $transactionId,
                'payment_status'          => $newPaymentStatus,
            ]);

            // Jika pembayaran baru saja menjadi 'paid', jalankan markAsPaid() untuk set paid_at dan konfirmasi booking
            if ($newPaymentStatus === PaymentStatus::PAID && !$wasPaid) {
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
