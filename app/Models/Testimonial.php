<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Testimonial extends Model
{
    use HasFactory;

    protected $fillable = [
        'guest_id',
        'name',
        'message',
        'rating',
        'image',
        'is_approved',
    ];

    protected $casts = [
        'rating'      => 'integer',
        'is_approved' => 'boolean',
    ];

    // ==================== RELATIONS ====================

    public function guest(): BelongsTo
    {
        return $this->belongsTo(Guest::class);
    }

    // ==================== ACCESSORS ====================

    public function getImageUrlAttribute(): ?string
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }
        return null;
    }

    public function getStarsAttribute(): array
    {
        return [
            'filled' => $this->rating,
            'empty'  => 5 - $this->rating,
        ];
    }

    // ==================== SCOPES ====================

    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    public function scopePending($query)
    {
        return $query->where('is_approved', false);
    }
}
