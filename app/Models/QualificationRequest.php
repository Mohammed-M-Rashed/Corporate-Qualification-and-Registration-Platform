<?php

namespace App\Models;

use App\Enums\QualificationRequestStatus;
use App\Enums\ReviewStage;
use App\Enums\ReviewStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class QualificationRequest extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'company_id',
        'request_number',
        'status',
        'approved_by',
        'approved_at',
        'current_review_stage',
        'legal_review_status',
        'technical_review_status',
        'financial_review_status',
        'legal_reviewed_by',
        'technical_reviewed_by',
        'financial_reviewed_by',
        'legal_reviewed_at',
        'technical_reviewed_at',
        'financial_reviewed_at',
    ];

    protected $casts = [
        'status' => QualificationRequestStatus::class,
        'current_review_stage' => ReviewStage::class,
        'legal_review_status' => ReviewStatus::class,
        'technical_review_status' => ReviewStatus::class,
        'financial_review_status' => ReviewStatus::class,
        'approved_at' => 'datetime',
        'legal_reviewed_at' => 'datetime',
        'technical_reviewed_at' => 'datetime',
        'financial_reviewed_at' => 'datetime',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function legalReviewedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'legal_reviewed_by');
    }

    public function technicalReviewedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'technical_reviewed_by');
    }

    public function financialReviewedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'financial_reviewed_by');
    }

    public function legalDocuments(): HasMany
    {
        return $this->hasMany(LegalDocument::class);
    }

    public function technicalDocuments(): HasMany
    {
        return $this->hasMany(TechnicalDocument::class);
    }

    public function financialDocuments(): HasMany
    {
        return $this->hasMany(FinancialDocument::class);
    }

    public function requestActions(): HasMany
    {
        return $this->hasMany(RequestAction::class);
    }

    public function rejectionReasons(): BelongsToMany
    {
        return $this->belongsToMany(RejectionReason::class, 'qualification_request_rejection_reason')
            ->withTimestamps();
    }

    public function committeeReviews(): HasMany
    {
        return $this->hasMany(CommitteeReview::class);
    }

    public function scopeNew($query)
    {
        return $query->where('status', QualificationRequestStatus::New);
    }

    public function scopeUnderReview($query)
    {
        return $query->where('status', QualificationRequestStatus::UnderReview);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', QualificationRequestStatus::Approved);
    }

    public function scopeRejected($query)
    {
        return $query->where('status', QualificationRequestStatus::Rejected);
    }

    // Scopes للفلترة حسب المرحلة
    public function scopeAtStage($query, ReviewStage $stage)
    {
        return $query->where('current_review_stage', $stage);
    }

    public function scopeLegalStage($query)
    {
        return $query->where('current_review_stage', ReviewStage::Legal);
    }

    public function scopeTechnicalStage($query)
    {
        return $query->where('current_review_stage', ReviewStage::Technical);
    }

    public function scopeFinancialStage($query)
    {
        return $query->where('current_review_stage', ReviewStage::Financial);
    }

    public function scopeChairmanStage($query)
    {
        return $query->where('current_review_stage', ReviewStage::Chairman);
    }
}
