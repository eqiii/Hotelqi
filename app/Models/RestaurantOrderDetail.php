<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RestaurantOrderDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'restaurant_order_id',
        'restaurant_menu_id',
        'quantity',
        'price',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    // ==================== RELATIONS ====================

    public function order(): BelongsTo
    {
        return $this->belongsTo(RestaurantOrder::class, 'restaurant_order_id');
    }

    public function menu(): BelongsTo
    {
        return $this->belongsTo(RestaurantMenu::class, 'restaurant_menu_id');
    }

    // ==================== ACCESSORS ====================

    public function getSubtotalAttribute(): float
    {
        return $this->quantity * $this->price;
    }
}
