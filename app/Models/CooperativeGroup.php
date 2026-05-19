<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class CooperativeGroup extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'description', 'created_by', 'region',
        'invite_code', 'member_count', 'is_active',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'cooperative_members')
            ->withPivot('role', 'joined_at')
            ->withTimestamps();
    }

    public function messages()
    {
        return $this->hasMany(CooperativeMessage::class, 'cooperative_group_id');
    }

    public static function generateInviteCode(): string
    {
        do {
            $code = strtoupper(substr(md5(uniqid()), 0, 8));
        } while (self::where('invite_code', $code)->exists());
        return $code;
    }
}
