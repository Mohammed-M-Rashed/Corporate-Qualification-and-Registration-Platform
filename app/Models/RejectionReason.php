<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class RejectionReason extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'reason',
        'is_active',
        'order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function qualificationRequests(): BelongsToMany
    {
        return $this->belongsToMany(QualificationRequest::class, 'qualification_request_rejection_reason')
            ->withTimestamps();
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
