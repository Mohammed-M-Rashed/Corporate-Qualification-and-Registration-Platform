<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\MemberType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'committee_id',
        'member_type',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'member_type' => MemberType::class,
        ];
    }

    public function committee(): BelongsTo
    {
        return $this->belongsTo(Committee::class);
    }

    /** اللجان التي ينتمي إليها المستخدم كعضو (عبر committee_members) */
    public function committees(): BelongsToMany
    {
        return $this->belongsToMany(Committee::class, 'committee_members')
            ->withTimestamps();
    }

    public function requestActions(): HasMany
    {
        return $this->hasMany(RequestAction::class);
    }

    public function legalReviewedRequests(): HasMany
    {
        return $this->hasMany(QualificationRequest::class, 'legal_reviewed_by');
    }

    public function technicalReviewedRequests(): HasMany
    {
        return $this->hasMany(QualificationRequest::class, 'technical_reviewed_by');
    }

    public function financialReviewedRequests(): HasMany
    {
        return $this->hasMany(QualificationRequest::class, 'financial_reviewed_by');
    }

    public function approvedRequests(): HasMany
    {
        return $this->hasMany(QualificationRequest::class, 'approved_by');
    }
}
