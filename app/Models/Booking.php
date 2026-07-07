<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'guest_id',
        'room_id',
        'check_in',
        'check_out',
        'total_nights',
        'total_price',
        'status',
        'notes',
    ];

    protected $casts = [
        'check_in'     => 'date',
        'check_out'    => 'date',
        'total_price'  => 'decimal:2',
    ];

    // ==================== RELATIONS ====================

    public function guest(): BelongsTo
    {
        return $this->belongsTo(Guest::class);
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    public function histories(): HasMany
    {
        return $this->hasMany(BookingHistory::class);
    }

    public function restaurantOrders(): HasMany
    {
        return $this->hasMany(RestaurantOrder::class);
    }

    // ==================== ACCESSORS ====================

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'pending'     => 'bg-yellow-100 text-yellow-800',
            'confirmed'   => 'bg-blue-100 text-blue-800',
            'checked_in'  => 'bg-green-100 text-green-800',
            'checked_out' => 'bg-gray-100 text-gray-800',
            'cancelled'   => 'bg-red-100 text-red-800',
            default       => 'bg-gray-100 text-gray-800',
        };
    }

    public function getInvoiceNumberAttribute(): string
    {
        return 'INV-' . str_pad($this->id, 6, '0', STR_PAD_LEFT) . '-' . $this->created_at->format('Ymd');
    }

    // ==================== SCOPES ====================

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    public function scopeCheckedIn($query)
    {
        return $query->where('status', 'checked_in');
    }

    public function scopeCheckedOut($query)
    {
        return $query->where('status', 'checked_out');
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    public function scopeActive($query)
    {
        return $query->whereIn('status', ['pending', 'confirmed', 'checked_in']);
    }

    public function scopeByDateRange($query, ?string $start, ?string $end)
    {
        if ($start) $query->where('check_in', '>=', $start);
        if ($end)   $query->where('check_out', '<=', $end);
        return $query;
    }

    // ==================== HELPERS ====================

    /**
     * Update status dan record ke history
     */
    public function updateStatus(string $newStatus, ?string $notes = null, ?int $changedBy = null): void
    {
        $oldStatus = $this->status;
        $this->update(['status' => $newStatus]);

        $this->histories()->create([
            'status'     => $newStatus,
            'notes'      => $notes ?? "Status changed from {$oldStatus} to {$newStatus}",
            'changed_by' => $changedBy,
        ]);
    }

    public function isOverdue(): bool
    {
        return $this->status === 'confirmed' && $this->check_in->isPast();
    }

    public function nightsStayed(): int
    {
        return $this->check_in->diffInDays($this->check_out);
    }
}
