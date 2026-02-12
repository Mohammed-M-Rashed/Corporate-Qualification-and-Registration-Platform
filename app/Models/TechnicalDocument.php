<?php

namespace App\Models;

use App\Enums\TechnicalDocumentType;
use App\Enums\ExperienceLevel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TechnicalDocument extends Model
{
    protected $fillable = [
        'qualification_request_id',
        'experience_level',
        'document_type',
        'file_path',
        'file_name',
        'file_size',
    ];

    protected $casts = [
        'experience_level' => ExperienceLevel::class,
        'document_type' => TechnicalDocumentType::class,
        'file_size' => 'integer',
    ];

    public function qualificationRequest(): BelongsTo
    {
        return $this->belongsTo(QualificationRequest::class);
    }
}
