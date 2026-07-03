<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class RoomType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'base_price',
        'max_guest',
        'total_bed',
        'image',
    ];

    protected $casts = [
        'base_price' => 'decimal:2',
    ];

    // ==================== RELATIONS ====================

    public function rooms(): HasMany
    {
        return $this->hasMany(Room::class);
    }

    public function facilities(): BelongsToMany
    {
        return $this->belongsToMany(Facility::class, 'room_facilities')
            ->withTimestamps();
    }

    public function roomFacilities(): HasMany
    {
        return $this->hasMany(RoomFacility::class);
    }

    public function dynamicPricings(): HasMany
    {
        return $this->hasMany(DynamicPricing::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasManyThrough(Booking::class, Room::class);
    }

    // ==================== ACCESSORS ====================

    public function getImageUrlAttribute(): string
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }
        return asset('images/default-room.jpg');
    }

    public function getAvailableRoomsCountAttribute(): int
    {
        return $this->rooms()->where('status', 'available')->count();
    }

    public function getTotalRoomsCountAttribute(): int
    {
        return $this->rooms()->count();
    }

    // ==================== SCOPES ====================

    /**
     * Scope untuk mencari kamar yang tersedia pada rentang tanggal tertentu
     */
    public function scopeAvailableBetween($query, string $checkIn, string $checkOut)
    {
        return $query->whereHas('rooms', function ($q) use ($checkIn, $checkOut) {
            $q->where('status', 'available')
                ->whereDoesntHave('bookings', function ($bq) use ($checkIn, $checkOut) {
                    $bq->whereIn('status', ['pending', 'confirmed', 'checked_in'])
                        ->where(function ($sub) use ($checkIn, $checkOut) {
                            $sub->whereBetween('check_in', [$checkIn, $checkOut])
                                ->orWhereBetween('check_out', [$checkIn, $checkOut])
                                ->orWhere(function ($sub2) use ($checkIn, $checkOut) {
                                    $sub2->where('check_in', '<=', $checkIn)
                                        ->where('check_out', '>=', $checkOut);
                                });
                        });
                });
        });
    }

    // ==================== HELPERS ====================

    /**
     * Menghitung harga dinamis berdasarkan tanggal
     */
    public function getPriceForDate(string $date): float
    {
        $pricing = $this->dynamicPricings()
            ->where('start_date', '<=', $date)
            ->where('end_date', '>=', $date)
            ->first();

        if ($pricing) {
            return ($this->base_price + $pricing->price_adjustment) * $pricing->price_multiplier;
        }

        return $this->base_price;
    }

    /**
     * Menghitung total harga untuk rentang tanggal
     */
    public function calculateTotalPrice(string $checkIn, string $checkOut): array
    {
        $start    = \Carbon\Carbon::parse($checkIn);
        $end      = \Carbon\Carbon::parse($checkOut);
        $nights   = $start->diffInDays($end);
        $breakdown = [];
        $total    = 0;

        for ($i = 0; $i < $nights; $i++) {
            $currentDate = $start->copy()->addDays($i)->toDateString();
            $price       = $this->getPriceForDate($currentDate);
            $breakdown[] = [
                'date'  => $currentDate,
                'price' => $price,
            ];
            $total += $price;
        }

        return [
            'total_nights' => $nights,
            'total_price'  => $total,
            'breakdown'    => $breakdown,
        ];
    }

    public function isAvailable(string $checkIn, string $checkOut): bool
    {
        return !$this->bookings()
            ->whereIn('status', ['pending', 'confirmed', 'checked_in'])
            ->where(function ($query) use ($checkIn, $checkOut) {
                $query->where('check_in', '<', $checkOut)
                    ->where('check_out', '>', $checkIn);
            })
            ->exists();
    }
}
