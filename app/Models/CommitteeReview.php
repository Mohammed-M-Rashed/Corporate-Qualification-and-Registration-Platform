<?php

namespace App\Models;

use App\Enums\MemberType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CommitteeReview extends Model
{
    protected $fillable = [
        'qualification_request_id',
        'user_id',
        'member_type',
        'status',
        'rejection_reason_ids',
        'notes',
        'reviewed_at',
    ];

    protected $casts = [
        'member_type' => MemberType::class,
        'rejection_reason_ids' => 'array',
        'reviewed_at' => 'datetime',
    ];

    public function qualificationRequest(): BelongsTo
    {
        return $this->belongsTo(QualificationRequest::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
