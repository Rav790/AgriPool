<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Wallet extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'balance',
    ];

    protected function casts(): array
    {
        return [
            'balance' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(WalletTransaction::class);
    }

    public function credit(float $amount, ?int $bookingId = null, ?string $description = null): WalletTransaction
    {
        $this->increment('balance', $amount);

        return $this->transactions()->create([
            'booking_id' => $bookingId,
            'type' => 'credit',
            'amount' => $amount,
            'description' => $description ?? 'Wallet top-up',
        ]);
    }

    public function debit(float $amount, ?int $bookingId = null, ?string $description = null): WalletTransaction
    {
        if ($this->balance < $amount) {
            throw new \RuntimeException(__('Insufficient wallet balance.'));
        }

        $this->decrement('balance', $amount);

        return $this->transactions()->create([
            'booking_id' => $bookingId,
            'type' => 'debit',
            'amount' => $amount,
            'description' => $description ?? 'Payment',
        ]);
    }

    public function hold(float $amount, int $bookingId, ?string $description = null): WalletTransaction
    {
        if ($this->balance < $amount) {
            throw new \RuntimeException(__('Insufficient wallet balance for hold.'));
        }

        $this->decrement('balance', $amount);

        return $this->transactions()->create([
            'booking_id' => $bookingId,
            'type' => 'hold',
            'amount' => $amount,
            'description' => $description ?? 'Payment held in escrow',
        ]);
    }

    public function release(float $amount, int $bookingId, ?string $description = null): WalletTransaction
    {
        $this->increment('balance', $amount);

        return $this->transactions()->create([
            'booking_id' => $bookingId,
            'type' => 'release',
            'amount' => $amount,
            'description' => $description ?? 'Payment released from escrow',
        ]);
    }

    public function refund(float $amount, int $bookingId, ?string $description = null): WalletTransaction
    {
        $this->increment('balance', $amount);

        return $this->transactions()->create([
            'booking_id' => $bookingId,
            'type' => 'refund',
            'amount' => $amount,
            'description' => $description ?? 'Payment refunded',
        ]);
    }
}
