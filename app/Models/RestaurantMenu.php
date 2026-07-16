<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RestaurantMenu extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'category',
        'image',
        'is_available',
        'stock_quantity',
    ];

    protected $casts = [
        'price'          => 'decimal:2',
        'is_available'   => 'boolean',
        'stock_quantity' => 'integer',
        'category'       => 'string',
    ];

    public const CATEGORIES = [
        'food'    => 'Makanan',
        'drink'   => 'Minuman',
        'dessert' => 'Dessert',
        'snack'   => 'Snack',
    ];

    public function getCategoryLabelAttribute(): string
    {
        return self::CATEGORIES[$this->category] ?? ucfirst($this->category);
    }

    // ==================== RELATIONS ====================

    public function orderDetails(): HasMany
    {
        return $this->hasMany(RestaurantOrderDetail::class);
    }

    // ==================== ACCESSORS ====================

    public function getImageUrlAttribute(): string
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }
        return asset('images/default-food.jpg');
    }

    // ==================== SCOPES ====================

    public function scopeAvailable($query)
    {
        return $query->where('is_available', true);
    }

    public function scopeByCategory($query, string $category)
    {
        return $query->where('category', $category);
    }
}
