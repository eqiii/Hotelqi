<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DynamicPricing extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_type_id',
        'name',
        'type',
        'start_date',
        'end_date',
        'price_adjustment',
        'price_multiplier',
    ];

    protected $casts = [
        'start_date'       => 'date',
        'end_date'         => 'date',
        'price_adjustment' => 'decimal:2',
        'price_multiplier' => 'decimal:2',
    ];

    // ==================== RELATIONS ====================

    public function roomType(): BelongsTo
    {
        return $this->belongsTo(RoomType::class);
    }

    // ==================== ACCESSORS ====================

    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            'weekend'     => 'Weekend',
            'holiday'     => 'Hari Libur',
            'high_season' => 'High Season',
            'low_season'  => 'Low Season',
            default       => ucfirst($this->type),
        };
    }

    public function getTypeBadgeAttribute(): string
    {
        return match ($this->type) {
            'weekend'     => 'bg-blue-100 text-blue-800',
            'holiday'     => 'bg-red-100 text-red-800',
            'high_season' => 'bg-orange-100 text-orange-800',
            'low_season'  => 'bg-green-100 text-green-800',
            default       => 'bg-gray-100 text-gray-800',
        };
    }

    // ==================== SCOPES ====================

    public function scopeActive($query)
    {
        return $query->where('start_date', '<=', now())
            ->where('end_date', '>=', now());
    }

    public function scopeForDate($query, string $date)
    {
        return $query->where('start_date', '<=', $date)
            ->where('end_date', '>=', $date);
    }

    // ==================== HELPERS ====================

    public function isActive(): bool
    {
        return now()->between($this->start_date, $this->end_date);
    }

    /**
     * Hitung final price untuk room type
     */
    public function calculatePrice(float $basePrice): float
    {
        return ($basePrice + $this->price_adjustment) * $this->price_multiplier;
    }
}
