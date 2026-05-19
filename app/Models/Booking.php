<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'request_id',
        'listing_id',
        'farmer_id',
        'transporter_id',
        'allocated_tons',
        'total_price',
        'status',
        'payment_status',
        'payment_mode',
        'pickup_confirmed_at',
        'delivery_confirmed_at',
    ];

    protected function casts(): array
    {
        return [
            'allocated_tons' => 'decimal:2',
            'total_price' => 'decimal:2',
            'pickup_confirmed_at' => 'datetime',
            'delivery_confirmed_at' => 'datetime',
        ];
    }

    public function transportRequest(): BelongsTo
    {
        return $this->belongsTo(TransportRequest::class, 'request_id');
    }

    public function transportListing(): BelongsTo
    {
        return $this->belongsTo(TransportListing::class, 'listing_id');
    }

    public function farmer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'farmer_id');
    }

    public function transporter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'transporter_id');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function trackingUpdates(): HasMany
    {
        return $this->hasMany(TrackingUpdate::class);
    }

    public function walletTransactions(): HasMany
    {
        return $this->hasMany(WalletTransaction::class);
    }

    public function isDelivered(): bool
    {
        return $this->status === 'delivered';
    }

    public function canBeReviewedBy(User $user): bool
    {
        if (!$this->isDelivered()) return false;

        // User must be a party in this booking
        if ($this->farmer_id !== $user->id && $this->transporter_id !== $user->id) {
            return false;
        }

        return !$this->reviews()->where('reviewer_id', $user->id)->exists();
    }
}
