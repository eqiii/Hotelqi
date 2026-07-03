<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'provider',
        'provider_id',
        'email_verified_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
    ];

    // ==================== RELATIONS ====================

    public function guest(): HasOne
    {
        return $this->hasOne(Guest::class);
    }

    public function otps(): HasMany
    {
        return $this->hasMany(Otp::class);
    }

    public function bookingHistories(): HasMany
    {
        return $this->hasMany(BookingHistory::class, 'changed_by');
    }

    // ==================== SCOPES ====================

    public function scopeAdmins($query)
    {
        return $query->where('role', 'admin');
    }

    public function scopeGuests($query)
    {
        return $query->where('role', 'guest');
    }

    // ==================== HELPERS ====================

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isGuest(): bool
    {
        return $this->role === 'guest';
    }

    /**
     * Mendapatkan OTP terakhir yang masih berlaku
     */
    public function latestValidOtp(string $type = 'registration')
    {
        return $this->otps()
            ->where('type', $type)
            ->where('expires_at', '>', now())
            ->latest()
            ->first();
    }
}
