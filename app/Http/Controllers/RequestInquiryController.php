<?php

namespace App\Http\Controllers;

use App\Models\QualificationRequest;
use Illuminate\Http\Request;

class RequestInquiryController extends Controller
{
    public function inquiry(Request $request)
    {
        $requestNumber = $request->input('request_number');

        $qualificationRequest = QualificationRequest::where('request_number', $requestNumber)
            ->with(['company', 'legalDocuments', 'technicalDocuments', 'financialDocuments', 'rejectionReasons'])
            ->first();

        if (!$qualificationRequest) {
            return response()->json(['error' => 'لم يتم العثور على الطلب'], 404);
        }

        $rejectionReasons = $qualificationRequest->rejectionReasons->pluck('reason')->toArray();
        
        return response()->json([
            'request_number' => $qualificationRequest->request_number,
            'company_name' => $qualificationRequest->company->name,
            'status' => $qualificationRequest->status->value,
            'status_ar' => $this->getStatusArabic($qualificationRequest->status),
            'rejection_reasons' => $rejectionReasons,
            'created_at' => $qualificationRequest->created_at->format('Y-m-d H:i:s'),
        ]);
    }

    protected function getStatusArabic($status): string
    {
        if ($status instanceof \App\Enums\QualificationRequestStatus) {
            return match($status) {
                \App\Enums\QualificationRequestStatus::New => 'جديد',
                \App\Enums\QualificationRequestStatus::UnderReview => 'قيد المراجعة',
                \App\Enums\QualificationRequestStatus::Approved => 'مقبول',
                \App\Enums\QualificationRequestStatus::Rejected => 'مرفوض',
            };
        }
        
        return match($status) {
            'new' => 'جديد',
            'under_review' => 'قيد المراجعة',
            'approved' => 'مقبول',
            'rejected' => 'مرفوض',
            default => $status,
        };
    }
}
