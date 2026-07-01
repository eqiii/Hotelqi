<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookingHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'status',
        'notes',
        'changed_by',
    ];

    // ==================== RELATIONS ====================

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function changedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'changed_by');
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
}
