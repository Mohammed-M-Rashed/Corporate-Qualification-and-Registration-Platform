<?php

namespace App\Models;

use App\Enums\RequestActionType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RequestAction extends Model
{
    protected $fillable = [
        'qualification_request_id',
        'user_id',
        'action_type',
        'notes',
    ];

    protected $casts = [
        'action_type' => RequestActionType::class,
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
