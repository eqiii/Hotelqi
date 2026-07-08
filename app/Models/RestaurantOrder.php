<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RestaurantOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'guest_id',
        'booking_id',
        'invoice_number',
        'subtotal',
        'tax',
        'total',
        'total_price',
        'payment_method',
        'payment_status',
        'order_status',
        'serve_type',
        'serve_time',
        'dining_type',
        'guest_name',
        'room_number',
        'notes',
        'paid_at',
        'status',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'tax' => 'decimal:2',
        'total' => 'decimal:2',
        'total_price' => 'decimal:2',
        'serve_time' => 'datetime',
        'paid_at' => 'datetime',
    ];

    // ==================== RELATIONS ====================

    public function guest(): BelongsTo
    {
        return $this->belongsTo(Guest::class);
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function details(): HasMany
    {
        return $this->hasMany(RestaurantOrderDetail::class);
    }

    // ==================== HELPERS ====================

    public function recalculateTotal(): void
    {
        $subtotal = $this->details()->sum(\DB::raw('quantity * price'));
        $tax = round($subtotal * 0.1, 2);
        $total = $subtotal + $tax;

        $this->update([
            'subtotal' => $subtotal,
            'tax' => $tax,
            'total' => $total,
            'status' => 'pending',
        ]);
    }

    public function markAsPaid(): void
    {
        $this->update([
            'payment_status' => 'paid',
            'order_status' => 'confirmed',
            'paid_at' => now(),
        ]);
    }

    public function getInvoiceNumberAttribute(): string
    {
        return $this->attributes['invoice_number'] ?? 'RST-' . str_pad((string) $this->id, 6, '0', STR_PAD_LEFT);
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->order_status) {
            'pending_payment' => 'bg-yellow-100 text-yellow-800',
            'confirmed' => 'bg-blue-100 text-blue-800',
            'cooking' => 'bg-orange-100 text-orange-800',
            'ready' => 'bg-emerald-100 text-emerald-800',
            'delivering' => 'bg-purple-100 text-purple-800',
            'completed' => 'bg-green-100 text-green-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }
}
