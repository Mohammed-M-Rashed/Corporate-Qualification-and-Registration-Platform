<?php

namespace App\Http\Controllers;

use App\Models\QualificationRequest;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class QualificationRequestPdfController extends Controller
{
    public function generatePdf(QualificationRequest $qualificationRequest)
    {
        $qualificationRequest->load([
            'company',
            'legalDocuments',
            'technicalDocuments',
            'financialDocuments',
            'reviewedBy',
            'approvedBy',
            'requestActions.user'
        ]);

        $pdf = Pdf::loadView('pdf.qualification-request', [
            'request' => $qualificationRequest,
            'company' => $qualificationRequest->company,
        ]);

        return $pdf->download('qualification-request-' . $qualificationRequest->request_number . '.pdf');
    }

    public function previewPdf(QualificationRequest $qualificationRequest)
    {
        $qualificationRequest->load([
            'company',
            'legalDocuments',
            'technicalDocuments',
            'financialDocuments',
            'reviewedBy',
            'approvedBy',
            'requestActions.user'
        ]);

        $pdf = Pdf::loadView('pdf.qualification-request', [
            'request' => $qualificationRequest,
            'company' => $qualificationRequest->company,
        ]);

        return $pdf->stream('qualification-request-' . $qualificationRequest->request_number . '.pdf');
    }
}
