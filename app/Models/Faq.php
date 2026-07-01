<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    use HasFactory;

    protected $table = 'faqs';

    protected $fillable = [
        'question',
        'answer',
        'order',
    ];

    protected $casts = [
        'order' => 'integer',
    ];

    // ==================== SCOPES ====================

    public function scopeOrdered($query)
    {
        return $query->orderBy('order', 'asc');
    }
}
