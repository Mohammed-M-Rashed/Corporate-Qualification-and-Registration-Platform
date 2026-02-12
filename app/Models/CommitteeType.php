<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CommitteeType extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'name_ar',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function committees(): HasMany
    {
        return $this->hasMany(Committee::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
