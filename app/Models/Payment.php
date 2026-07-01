<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'amount',
        'payment_method',
        'payment_status',
        'midtrans_transaction_id',
        'midtrans_snap_token',
        'proof_of_payment',
        'paid_at',
    ];

    protected $casts = [
        'amount'  => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    // ==================== RELATIONS ====================

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    // ==================== ACCESSORS ====================

    public function getProofUrlAttribute(): ?string
    {
        if ($this->proof_of_payment) {
            return asset('storage/' . $this->proof_of_payment);
        }
        return null;
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->payment_status) {
            'unpaid'  => 'bg-yellow-100 text-yellow-800',
            'paid'    => 'bg-green-100 text-green-800',
            'failed'  => 'bg-red-100 text-red-800',
            'expired' => 'bg-gray-100 text-gray-800',
            'refunded' => 'bg-purple-100 text-purple-800',
            default   => 'bg-gray-100 text-gray-800',
        };
    }

    // ==================== HELPERS ====================

    public function markAsPaid(): void
    {
        $this->update([
            'payment_status' => 'paid',
            'paid_at'        => now(),
        ]);

        // Auto confirm booking jika belum confirmed
        if ($this->booking && $this->booking->status === 'pending') {
            $this->booking->updateStatus('confirmed', 'Payment verified automatically');
        }
    }

    public function isPaid(): bool
    {
        return $this->payment_status === 'paid';
    }
}
