<?php

namespace App\Http\Controllers;

use App\Models\QualificationRequest;
use App\Services\QualificationWorkflowService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RequestActionController extends Controller
{
    protected $workflowService;

    public function __construct(QualificationWorkflowService $workflowService)
    {
        $this->workflowService = $workflowService;
    }

    public function initialApproval(Request $request, QualificationRequest $qualificationRequest)
    {
        $this->workflowService->initialApproval(
            $qualificationRequest,
            Auth::id(),
            $request->input('notes')
        );

        return response()->json(['success' => true, 'message' => 'تم قبول الطلب مبدئياً']);
    }

    public function initialRejection(Request $request, QualificationRequest $qualificationRequest)
    {
        $request->validate([
            'reason' => 'required|string',
        ]);

        $this->workflowService->initialRejection(
            $qualificationRequest,
            Auth::id(),
            $request->input('reason')
        );

        return response()->json(['success' => true, 'message' => 'تم رفض الطلب']);
    }

    public function finalApproval(Request $request, QualificationRequest $qualificationRequest)
    {
        $this->workflowService->finalApproval(
            $qualificationRequest,
            Auth::id()
        );

        return response()->json(['success' => true, 'message' => 'تم قبول الطلب نهائياً']);
    }

    public function finalRejection(Request $request, QualificationRequest $qualificationRequest)
    {
        $request->validate([
            'reason' => 'required|string',
        ]);

        $this->workflowService->finalRejection(
            $qualificationRequest,
            Auth::id(),
            $request->input('reason')
        );

        return response()->json(['success' => true, 'message' => 'تم رفض الطلب نهائياً']);
    }
}
