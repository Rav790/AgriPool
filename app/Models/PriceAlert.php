<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PriceAlert extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'crop_type', 'target_price',
        'condition', 'is_active', 'is_triggered', 'triggered_at',
    ];

    protected function casts(): array
    {
        return [
            'target_price' => 'decimal:2',
            'is_active' => 'boolean',
            'is_triggered' => 'boolean',
            'triggered_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
