<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TransportRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'farmer_id',
        'crop_type',
        'quantity_tons',
        'packaging_type',
        'pickup_lat',
        'pickup_lng',
        'pickup_address',
        'destination_market_id',
        'required_date',
        'is_perishable',
        'status',
        'special_instructions',
    ];

    protected function casts(): array
    {
        return [
            'quantity_tons' => 'decimal:2',
            'pickup_lat' => 'decimal:7',
            'pickup_lng' => 'decimal:7',
            'required_date' => 'date',
            'is_perishable' => 'boolean',
        ];
    }

    public function farmer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'farmer_id');
    }

    public function destinationMarket(): BelongsTo
    {
        return $this->belongsTo(Market::class, 'destination_market_id');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'request_id');
    }

    /**
     * Check if this request has a spoilage risk (perishable + pickup > 24h away).
     */
    public function hasSpoilageRisk(): bool
    {
        if (!$this->is_perishable) {
            return false;
        }

        return $this->required_date->diffInHours(now()) > 24;
    }
}
