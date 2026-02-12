<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Committee extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'committee_type_id',
        'name',
        'chairman_id',
        'establishment_decision_file',
        'start_date',
        'end_date',
        'is_active',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function type(): BelongsTo
    {
        return $this->belongsTo(CommitteeType::class, 'committee_type_id');
    }

    public function chairman(): BelongsTo
    {
        return $this->belongsTo(User::class, 'chairman_id');
    }

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'committee_members')
            ->withTimestamps();
    }

    public function committeeMembers(): HasMany
    {
        return $this->hasMany(CommitteeMember::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
