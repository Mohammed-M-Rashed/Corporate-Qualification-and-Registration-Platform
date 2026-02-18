<?php

namespace App\Http\Controllers;

use App\Models\LegalDocument;
use App\Models\TechnicalDocument;
use App\Models\FinancialDocument;
use App\Models\QualificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class DocumentViewController extends Controller
{
    /**
     * عرض ملف وكالة الشركة لطلب التأهيل
     */
    public function viewAgentDocument(QualificationRequest $qualificationRequest)
    {
        Gate::authorize('view', $qualificationRequest);

        $company = $qualificationRequest->company;
        if (!$company || !$company->is_agent || !$company->agent_document_path) {
            abort(404, 'ملف الوكالة غير موجود');
        }

        $path = str_replace('\\', '/', $company->agent_document_path);
        if (!Storage::disk('public')->exists($path)) {
            abort(404, 'الملف غير موجود');
        }

        $filePath = Storage::disk('public')->path($path);
        if (!is_file($filePath)) {
            abort(404, 'الملف غير موجود');
        }

        $mimeType = mime_content_type($filePath) ?: 'application/pdf';
        $fileName = basename($path);

        return response()->file($filePath, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="' . $fileName . '"',
            'Cache-Control' => 'public, max-age=3600',
        ]);
    }
    /**
     * View a legal document
     */
    public function viewLegalDocument(LegalDocument $document)
    {
        Gate::authorize('view', $document->qualificationRequest);
        
        return $this->viewDocument($document);
    }

    /**
     * View a technical document
     */
    public function viewTechnicalDocument(TechnicalDocument $document)
    {
        Gate::authorize('view', $document->qualificationRequest);
        
        return $this->viewDocument($document);
    }

    /**
     * View a financial document
     */
    public function viewFinancialDocument(FinancialDocument $document)
    {
        Gate::authorize('view', $document->qualificationRequest);
        
        return $this->viewDocument($document);
    }

    /**
     * Download a legal document
     */
    public function downloadLegalDocument(LegalDocument $document)
    {
        Gate::authorize('view', $document->qualificationRequest);
        
        return $this->downloadDocument($document);
    }

    /**
     * Download a technical document
     */
    public function downloadTechnicalDocument(TechnicalDocument $document)
    {
        Gate::authorize('view', $document->qualificationRequest);
        
        return $this->downloadDocument($document);
    }

    /**
     * Download a financial document
     */
    public function downloadFinancialDocument(FinancialDocument $document)
    {
        Gate::authorize('view', $document->qualificationRequest);
        
        return $this->downloadDocument($document);
    }

    /**
     * Helper method to view any document
     */
    protected function viewDocument($document)
    {
        if (!Storage::disk('public')->exists($document->file_path)) {
            abort(404, 'الملف غير موجود');
        }

        $filePath = Storage::disk('public')->path($document->file_path);
        
        // Check if file exists
        if (!file_exists($filePath)) {
            abort(404, 'الملف غير موجود');
        }

        $mimeType = mime_content_type($filePath) ?: 'application/pdf';

        return response()->file($filePath, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="' . $document->file_name . '"',
            'Cache-Control' => 'public, max-age=3600',
        ]);
    }

    /**
     * Helper method to download any document
     */
    protected function downloadDocument($document)
    {
        if (!Storage::disk('public')->exists($document->file_path)) {
            abort(404, 'الملف غير موجود');
        }

        return Storage::disk('public')->download($document->file_path, $document->file_name);
    }
}

