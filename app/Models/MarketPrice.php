<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MarketPrice extends Model
{
    use HasFactory;

    protected $fillable = [
        'market_id',
        'crop_type',
        'price_per_quintal',
        'recorded_date',
        'recorded_by',
    ];

    protected function casts(): array
    {
        return [
            'price_per_quintal' => 'decimal:2',
            'recorded_date' => 'date',
        ];
    }

    public function market(): BelongsTo
    {
        return $this->belongsTo(Market::class);
    }

    public function recorder(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }
}
