<?php

namespace App\Models;

use App\Enums\LegalDocumentType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LegalDocument extends Model
{
    protected $fillable = [
        'qualification_request_id',
        'document_type',
        'file_path',
        'file_name',
        'file_size',
    ];

    protected $casts = [
        'document_type' => LegalDocumentType::class,
        'file_size' => 'integer',
    ];

    public function qualificationRequest(): BelongsTo
    {
        return $this->belongsTo(QualificationRequest::class);
    }
}
