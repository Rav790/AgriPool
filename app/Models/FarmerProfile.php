<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FarmerProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'farm_location_lat',
        'farm_location_lng',
        'farm_address',
        'total_land_acres',
    ];

    protected function casts(): array
    {
        return [
            'farm_location_lat' => 'decimal:7',
            'farm_location_lng' => 'decimal:7',
            'total_land_acres' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
