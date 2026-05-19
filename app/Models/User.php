<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'phone',
        'email',
        'password',
        'role',
        'language_preference',
        'is_verified',
        'profile_photo',
        'kyc_status',
        'aadhaar_number',
        'aadhaar_document',
        'pan_number',
        'pan_document',
        'bank_account_number',
        'bank_ifsc',
        'bank_name',
        'address',
        'city',
        'state',
        'pincode',
        'trust_score',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_verified' => 'boolean',
        ];
    }

    // ── Role helpers ──────────────────────────────────────

    public function isFarmer(): bool
    {
        return $this->role === 'farmer';
    }

    public function isTransporter(): bool
    {
        return $this->role === 'transporter';
    }

    public function isAgent(): bool
    {
        return $this->role === 'agent';
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    // ── Relationships ─────────────────────────────────────

    public function farmerProfile(): HasOne
    {
        return $this->hasOne(FarmerProfile::class);
    }

    public function transporterProfile(): HasOne
    {
        return $this->hasOne(TransporterProfile::class);
    }

    public function transportRequests(): HasMany
    {
        return $this->hasMany(TransportRequest::class, 'farmer_id');
    }

    public function transportListings(): HasMany
    {
        return $this->hasMany(TransportListing::class, 'transporter_id');
    }

    public function farmerBookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'farmer_id');
    }

    public function transporterBookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'transporter_id');
    }

    public function sentMessages(): HasMany
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function receivedMessages(): HasMany
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    public function reviewsGiven(): HasMany
    {
        return $this->hasMany(Review::class, 'reviewer_id');
    }

    public function reviewsReceived(): HasMany
    {
        return $this->hasMany(Review::class, 'reviewee_id');
    }

    public function wallet(): HasOne
    {
        return $this->hasOne(Wallet::class);
    }

    public function averageRating(): float
    {
        return round($this->reviewsReceived()->avg('rating') ?? 0, 1);
    }

    public function unreadMessagesCount(): int
    {
        return $this->receivedMessages()->where('is_read', false)->count();
    }

    public function unreadNotificationsCount(): int
    {
        return $this->unreadNotifications()->count();
    }

    public function cooperativeGroups()
    {
        return $this->belongsToMany(CooperativeGroup::class, 'cooperative_members')
            ->withPivot('role', 'joined_at')
            ->withTimestamps();
    }

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }

    public function priceAlerts()
    {
        return $this->hasMany(PriceAlert::class);
    }

    public function helpTickets()
    {
        return $this->hasMany(HelpTicket::class);
    }

    public function disputes()
    {
        return $this->hasMany(Dispute::class, 'raised_by');
    }

    public function kycBadge(): string
    {
        return match ($this->kyc_status) {
            'verified' => '✅ Verified',
            'pending' => '⏳ Pending',
            'rejected' => '❌ Rejected',
            default => '📋 Not Submitted',
        };
    }

    public function trustScore(): int
    {
        $score = 0;
        $score += $this->is_verified ? 20 : 0;
        $score += $this->kyc_status === 'verified' ? 30 : 0;
        $score += min($this->reviewsReceived()->count() * 5, 25);
        $score += $this->averageRating() >= 4 ? 25 : ($this->averageRating() >= 3 ? 15 : 0);
        return min($score, 100);
    }
}
