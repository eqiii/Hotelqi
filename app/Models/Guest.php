<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Guest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'full_name',
        'phone',
        'address',
        'ktp_number',
        'avatar',
    ];

    // ==================== RELATIONS ====================

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function restaurantOrders(): HasMany
    {
        return $this->hasMany(RestaurantOrder::class);
    }

    public function testimonials(): HasMany
    {
        return $this->hasMany(Testimonial::class);
    }

    // ==================== ACCESSORS ====================

    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->user->name) . '&background=random';
    }

    // ==================== HELPERS ====================

    public function totalSpent(): float
    {
        return $this->bookings()
            ->whereHas('payment', fn($q) => $q->where('payment_status', 'paid'))
            ->sum('total_price');
    }

    public function totalBookings(): int
    {
        return $this->bookings()->count();
    }

    public function isReturningGuest(): bool
    {
        return $this->bookings()
            ->where('status', 'checked_out')
            ->count() > 1;
    }
}
