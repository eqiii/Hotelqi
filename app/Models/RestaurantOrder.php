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
        'total_price',
        'status',
    ];

    protected $casts = [
        'total_price' => 'decimal:2',
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
        $total = $this->details()->sum(\DB::raw('quantity * price'));
        $this->update(['total_price' => $total]);
    }
}
