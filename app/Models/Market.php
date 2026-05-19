<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Market extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'location',
        'city',
        'state',
        'lat',
        'lng',
        'type',
    ];

    protected function casts(): array
    {
        return [
            'lat' => 'decimal:7',
            'lng' => 'decimal:7',
        ];
    }

    public function prices(): HasMany
    {
        return $this->hasMany(MarketPrice::class);
    }

    public function transportRequests(): HasMany
    {
        return $this->hasMany(TransportRequest::class, 'destination_market_id');
    }

    public function latestPrice(string $cropType): ?MarketPrice
    {
        return $this->prices()
            ->where('crop_type', $cropType)
            ->orderByDesc('recorded_date')
            ->first();
    }
}
