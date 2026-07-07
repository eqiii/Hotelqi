<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_type_id',
        'room_number',
        'status',
        'image',
    ];

    protected $casts = [
        'status' => 'string',
    ];

    // ==================== RELATIONS ====================

    public function roomType(): BelongsTo
    {
        return $this->belongsTo(RoomType::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    // ==================== SCOPES ====================

    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    public function scopeOccupied($query)
    {
        return $query->where('status', 'occupied');
    }

    public function scopeMaintenance($query)
    {
        return $query->where('status', 'maintenance');
    }

    /**
     * Scope untuk kamar yang available pada rentang tanggal tertentu
     */
    public function scopeAvailableBetween($query, string $checkIn, string $checkOut)
    {
        return $query->where('status', 'available')
            ->whereDoesntHave('bookings', function ($q) use ($checkIn, $checkOut) {
                $q->whereIn('status', ['pending', 'confirmed', 'checked_in'])
                    ->where(function ($sub) use ($checkIn, $checkOut) {
                        $sub->whereBetween('check_in', [$checkIn, $checkOut])
                            ->orWhereBetween('check_out', [$checkIn, $checkOut])
                            ->orWhere(function ($sub2) use ($checkIn, $checkOut) {
                                $sub2->where('check_in', '<=', $checkIn)
                                    ->where('check_out', '>=', $checkOut);
                            });
                    });
            });
    }

    // ==================== HELPERS ====================

    public function isActiveBooking(): bool
    {
        return $this->bookings()
            ->whereIn('status', ['confirmed', 'checked_in'])
            ->exists();
    }

    public function currentBooking(): ?Booking
    {
        return $this->bookings()
            ->whereIn('status', ['confirmed', 'checked_in'])
            ->latest()
            ->first();
    }

    public function getImageUrlAttribute(): string
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }

        if ($this->roomType) {
            return $this->roomType->image_url;
        }

        return asset('images/default-room.jpg');
    }
}
