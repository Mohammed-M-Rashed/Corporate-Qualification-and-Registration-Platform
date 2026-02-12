<?php

namespace App\Models;

use App\Enums\MemberType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CommitteeMember extends Model
{
    protected $fillable = [
        'committee_id',
        'user_id',
    ];

    public function committee(): BelongsTo
    {
        return $this->belongsTo(Committee::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Accessor للحصول على member_type من User
     */
    public function getMemberTypeAttribute()
    {
        return $this->user?->member_type;
    }
}
