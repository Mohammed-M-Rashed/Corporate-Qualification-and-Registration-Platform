<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Company extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'city_id',
        'commercial_register_number',
        'commercial_register_start_date',
        'commercial_register_end_date',
        'commercial_register_date',
        'email',
        'phone',
        'address',
        'is_agent',
        'agent_document_path',
        'is_qualified',
        'qualification_expiry_date',
        'is_active',
    ];

    protected $casts = [
        'commercial_register_start_date' => 'date',
        'commercial_register_end_date' => 'date',
        'commercial_register_date' => 'date',
        'qualification_expiry_date' => 'date',
        'is_qualified' => 'boolean',
        'is_active' => 'boolean',
        'is_agent' => 'boolean',
    ];

    public function qualificationRequests(): HasMany
    {
        return $this->hasMany(QualificationRequest::class);
    }

    public function activities(): BelongsToMany
    {
        return $this->belongsToMany(CompanyActivity::class, 'company_company_activity')
            ->withTimestamps();
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    // Legal, Technical, and Financial documents are accessed through QualificationRequest

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeQualified($query)
    {
        return $query->where('is_qualified', true);
    }
}
