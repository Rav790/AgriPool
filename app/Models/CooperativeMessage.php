<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CooperativeMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'cooperative_group_id',
        'user_id',
        'message',
        'attachment_path',
    ];

    public function group(): BelongsTo
    {
        return $this->belongsTo(CooperativeGroup::class, 'cooperative_group_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
