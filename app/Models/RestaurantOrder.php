<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class RestaurantOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'guest_id',
        'booking_id',
        'order_number',
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
        'midtrans_order_id',
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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

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

    // ==================== ACCESSORS ====================

    public function getOrderNumberDisplayAttribute(): string
    {
        return $this->attributes['order_number'] ?? 'RST-' . str_pad((string) $this->id, 4, '0', STR_PAD_LEFT);
    }

    public function getInvoiceNumberAttribute(): string
    {
        return $this->attributes['invoice_number'] ?? 'RST-' . str_pad((string) $this->id, 6, '0', STR_PAD_LEFT);
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->order_status) {
            'pending' => 'bg-yellow-100 text-yellow-800',
            'confirmed' => 'bg-blue-100 text-blue-800',
            'cooking' => 'bg-orange-100 text-orange-800',
            'ready' => 'bg-emerald-100 text-emerald-800',
            'delivering' => 'bg-purple-100 text-purple-800',
            'completed' => 'bg-green-100 text-green-800',
            'cancelled' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->order_status) {
            'pending' => 'Menunggu Konfirmasi',
            'confirmed' => 'Dikonfirmasi',
            'cooking' => 'Sedang Dimasak',
            'ready' => 'Siap Disajikan',
            'delivering' => 'Sedang Diantar',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
            default => ucfirst(str_replace('_', ' ', $this->order_status ?? 'unknown')),
        };
    }

    public function getPaymentStatusLabelAttribute(): string
    {
        return match ($this->payment_status) {
            'pending' => 'Menunggu Pembayaran',
            'paid' => 'Lunas',
            'failed' => 'Gagal',
            'expired' => 'Kedaluwarsa',
            'cancelled' => 'Dibatalkan',
            default => ucfirst($this->payment_status ?? 'unknown'),
        };
    }

    public function getDeliveryLabelAttribute(): string
    {
        return match ($this->dining_type) {
            'dine_in' => 'Dine In',
            'room_service' => 'Room Delivery',
            default => ucfirst(str_replace('_', ' ', $this->dining_type ?? 'unknown')),
        };
    }

    // ==================== HELPERS ====================

    public function recalculateTotal(): void
    {
        $subtotal = $this->details()->sum(DB::raw('quantity * price'));
        $tax = round($subtotal * 0.1, 2);
        $total = $subtotal + $tax;

        $this->update([
            'subtotal' => $subtotal,
            'tax' => $tax,
            'total' => $total,
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

    /**
     * Generate a sequential order number.
     */
    public static function generateOrderNumber(): string
    {
        $lastOrder = self::query()
            ->whereNotNull('order_number')
            ->orderBy('id', 'desc')
            ->first();

        $lastNumber = $lastOrder ? (int) substr($lastOrder->order_number, 4) : 0;
        $newNumber = $lastNumber + 1;

        return 'RST-' . str_pad((string) $newNumber, 4, '0', STR_PAD_LEFT);
    }
}
