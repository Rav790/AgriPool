<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TransportListing extends Model
{
    use HasFactory;

    protected $fillable = [
        'transporter_id',
        'route_from',
        'route_to',
        'route_from_lat',
        'route_from_lng',
        'route_to_lat',
        'route_to_lng',
        'available_date',
        'total_capacity',
        'remaining_capacity',
        'price_per_ton',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'available_date' => 'date',
            'total_capacity' => 'decimal:2',
            'remaining_capacity' => 'decimal:2',
            'price_per_ton' => 'decimal:2',
            'route_from_lat' => 'decimal:7',
            'route_from_lng' => 'decimal:7',
            'route_to_lat' => 'decimal:7',
            'route_to_lng' => 'decimal:7',
        ];
    }

    public function transporter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'transporter_id');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'listing_id');
    }

    public function usedCapacity(): float
    {
        return $this->total_capacity - $this->remaining_capacity;
    }

    public function capacityPercentage(): float
    {
        if ($this->total_capacity == 0) return 0;
        return round(($this->usedCapacity() / $this->total_capacity) * 100, 1);
    }

    public function isAvailable(): bool
    {
        return in_array($this->status, ['available', 'partially_booked']) && $this->remaining_capacity > 0;
    }
}
