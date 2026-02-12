<?php

namespace App\Models;

use App\Enums\FinancialDocumentType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FinancialDocument extends Model
{
    protected $fillable = [
        'qualification_request_id',
        'document_type',
        'file_path',
        'file_name',
        'file_size',
        'technical_notes',
    ];

    protected $casts = [
        'document_type' => FinancialDocumentType::class,
        'file_size' => 'integer',
    ];

    public function qualificationRequest(): BelongsTo
    {
        return $this->belongsTo(QualificationRequest::class);
    }
}
