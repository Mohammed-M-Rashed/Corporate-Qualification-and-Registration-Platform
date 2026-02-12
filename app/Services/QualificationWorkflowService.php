<?php

namespace App\Services;

use App\Models\QualificationRequest;
use App\Models\RequestAction;
use App\Models\Company;
use App\Models\CommitteeReview;
use App\Models\Committee;
use App\Mail\RequestApprovedMail;
use App\Mail\RequestRejectedMail;
use App\Notifications\InitialApprovalNotification;
use App\Notifications\FinalApprovalNotification;
use App\Notifications\FinalRejectionNotification;
use App\Enums\QualificationRequestStatus;
use App\Enums\RequestActionType;
use App\Enums\ReviewStage;
use App\Enums\ReviewStatus;
use App\Enums\MemberType;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class QualificationWorkflowService
{
    /**
     * مراجعة العضو القانوني
     */
    public function legalReview(QualificationRequest $request, $userId, $status, $rejectionReasonIds = [], $notes = null): void
    {
        DB::transaction(function () use ($request, $userId, $status, $rejectionReasonIds, $notes) {
            // التحقق من أن المرحلة الحالية هي legal
            if ($request->current_review_stage !== ReviewStage::Legal) {
                throw new \Exception('الطلب ليس في مرحلة المراجعة القانونية');
            }

            $reviewStatus = $status === 'approved' ? ReviewStatus::Approved : ReviewStatus::Rejected;

            // تحديث حالة المراجعة القانونية
            $request->update([
                'legal_review_status' => $reviewStatus,
                'legal_reviewed_by' => $userId,
                'legal_reviewed_at' => now(),
            ]);

            // إنشاء سجل المراجعة
            CommitteeReview::create([
                'qualification_request_id' => $request->id,
                'user_id' => $userId,
                'member_type' => MemberType::Legal,
                'status' => $status,
                'rejection_reason_ids' => !empty($rejectionReasonIds) ? $rejectionReasonIds : null,
                'notes' => $notes,
                'reviewed_at' => now(),
            ]);

            // ربط أسباب الرفض إذا كانت موجودة
            if (!empty($rejectionReasonIds)) {
                $request->rejectionReasons()->sync($rejectionReasonIds);
            }

            // إنشاء سجل الإجراء
            RequestAction::create([
                'qualification_request_id' => $request->id,
                'user_id' => $userId,
                'action_type' => $status === 'approved' ? RequestActionType::InitialApproval : RequestActionType::InitialRejection,
                'notes' => $notes ?? ($status === 'rejected' ? 'تم الرفض من العضو القانوني' : 'تم القبول من العضو القانوني'),
            ]);

            // بغض النظر عن القرار (قبول/رفض)، ينتقل الطلب إلى المرحلة الفنية
            $request->update([
                'current_review_stage' => ReviewStage::Technical,
                'technical_review_status' => ReviewStatus::Pending,
            ]);

            // إرسال إشعار للعضو الفني
            $this->notifyMemberForStage($request, MemberType::Technical);
        });
    }

    /**
     * مراجعة العضو الفني
     */
    public function technicalReview(QualificationRequest $request, $userId, $status, $rejectionReasonIds = [], $notes = null): void
    {
        DB::transaction(function () use ($request, $userId, $status, $rejectionReasonIds, $notes) {
            // التحقق من أن المرحلة الحالية هي technical
            if ($request->current_review_stage !== ReviewStage::Technical) {
                throw new \Exception('الطلب ليس في مرحلة المراجعة الفنية');
            }

            $reviewStatus = $status === 'approved' ? ReviewStatus::Approved : ReviewStatus::Rejected;

            // تحديث حالة المراجعة الفنية
            $request->update([
                'technical_review_status' => $reviewStatus,
                'technical_reviewed_by' => $userId,
                'technical_reviewed_at' => now(),
            ]);

            // إنشاء سجل المراجعة
            CommitteeReview::create([
                'qualification_request_id' => $request->id,
                'user_id' => $userId,
                'member_type' => MemberType::Technical,
                'status' => $status,
                'rejection_reason_ids' => !empty($rejectionReasonIds) ? $rejectionReasonIds : null,
                'notes' => $notes,
                'reviewed_at' => now(),
            ]);

            // ربط أسباب الرفض إذا كانت موجودة
            if (!empty($rejectionReasonIds)) {
                $request->rejectionReasons()->sync($rejectionReasonIds);
            }

            // إنشاء سجل الإجراء
            RequestAction::create([
                'qualification_request_id' => $request->id,
                'user_id' => $userId,
                'action_type' => $status === 'approved' ? RequestActionType::InitialApproval : RequestActionType::InitialRejection,
                'notes' => $notes ?? ($status === 'rejected' ? 'تم الرفض من العضو الفني' : 'تم القبول من العضو الفني'),
            ]);

            // بغض النظر عن القرار (قبول/رفض)، ينتقل الطلب إلى المرحلة المالية
            $request->update([
                'current_review_stage' => ReviewStage::Financial,
                'financial_review_status' => ReviewStatus::Pending,
            ]);

            // إرسال إشعار للعضو المالي
            $this->notifyMemberForStage($request, MemberType::Financial);
        });
    }

    /**
     * مراجعة العضو المالي
     */
    public function financialReview(QualificationRequest $request, $userId, $status, $rejectionReasonIds = [], $notes = null): void
    {
        DB::transaction(function () use ($request, $userId, $status, $rejectionReasonIds, $notes) {
            // التحقق من أن المرحلة الحالية هي financial
            if ($request->current_review_stage !== ReviewStage::Financial) {
                throw new \Exception('الطلب ليس في مرحلة المراجعة المالية');
            }

            $reviewStatus = $status === 'approved' ? ReviewStatus::Approved : ReviewStatus::Rejected;

            // تحديث حالة المراجعة المالية
            $request->update([
                'financial_review_status' => $reviewStatus,
                'financial_reviewed_by' => $userId,
                'financial_reviewed_at' => now(),
            ]);

            // إنشاء سجل المراجعة
            CommitteeReview::create([
                'qualification_request_id' => $request->id,
                'user_id' => $userId,
                'member_type' => MemberType::Financial,
                'status' => $status,
                'rejection_reason_ids' => !empty($rejectionReasonIds) ? $rejectionReasonIds : null,
                'notes' => $notes,
                'reviewed_at' => now(),
            ]);

            // ربط أسباب الرفض إذا كانت موجودة
            if (!empty($rejectionReasonIds)) {
                $request->rejectionReasons()->sync($rejectionReasonIds);
            }

            // إنشاء سجل الإجراء
            RequestAction::create([
                'qualification_request_id' => $request->id,
                'user_id' => $userId,
                'action_type' => $status === 'approved' ? RequestActionType::InitialApproval : RequestActionType::InitialRejection,
                'notes' => $notes ?? ($status === 'rejected' ? 'تم الرفض من العضو المالي' : 'تم القبول من العضو المالي'),
            ]);

            // بغض النظر عن القرار (قبول/رفض)، ينتقل الطلب إلى رئيس اللجنة
            $request->update([
                'current_review_stage' => ReviewStage::Chairman,
            ]);

            // إرسال إشعار لرئيس اللجنة
            $this->notifyChairman($request);
        });
    }

    /**
     * القبول النهائي من رئيس اللجنة
     */
    public function finalApproval(QualificationRequest $request, $userId): void
    {
        DB::transaction(function () use ($request, $userId) {
            // التحقق من أن المرحلة الحالية هي chairman
            if ($request->current_review_stage !== ReviewStage::Chairman) {
                throw new \Exception('الطلب ليس في مرحلة مراجعة رئيس اللجنة');
            }

            $request->update([
                'status' => QualificationRequestStatus::Approved,
                'approved_by' => $userId,
                'approved_at' => now(),
                'current_review_stage' => ReviewStage::Completed,
            ]);

            $request->company->update([
                'is_qualified' => true,
                'qualification_expiry_date' => Carbon::now()->addYear(),
            ]);

            RequestAction::create([
                'qualification_request_id' => $request->id,
                'user_id' => $userId,
                'action_type' => RequestActionType::FinalApproval,
            ]);

            Mail::to($request->company->email)->send(new RequestApprovedMail($request));

            // إرسال إشعار لجميع أعضاء اللجنة
            $committee = $this->getCommitteeForRequest($request);
            if ($committee) {
                foreach ($committee->members as $member) {
                    if ($member->user) {
                        $member->user->notify(new FinalApprovalNotification($request));
                    }
                }
            }
        });
    }

    /**
     * الرفض النهائي من رئيس اللجنة
     */
    public function finalRejection(QualificationRequest $request, $userId, $rejectionReasonIds = [], $reason = null): void
    {
        DB::transaction(function () use ($request, $userId, $rejectionReasonIds, $reason) {
            // التحقق من أن المرحلة الحالية هي chairman
            if ($request->current_review_stage !== ReviewStage::Chairman) {
                throw new \Exception('الطلب ليس في مرحلة مراجعة رئيس اللجنة');
            }

            // ربط أسباب الرفض
            if (!empty($rejectionReasonIds)) {
                $request->rejectionReasons()->sync($rejectionReasonIds);
            }

            $request->update([
                'status' => QualificationRequestStatus::Rejected,
                'approved_by' => $userId,
                'approved_at' => now(),
                'current_review_stage' => ReviewStage::Completed,
            ]);

            RequestAction::create([
                'qualification_request_id' => $request->id,
                'user_id' => $userId,
                'action_type' => RequestActionType::FinalRejection,
                'notes' => $reason,
            ]);

            Mail::to($request->company->email)->send(new RequestRejectedMail($request, $reason));

            // إرسال إشعار لجميع أعضاء اللجنة
            $committee = $this->getCommitteeForRequest($request);
            if ($committee) {
                foreach ($committee->members as $member) {
                    if ($member->user) {
                        $member->user->notify(new FinalRejectionNotification($request, $reason));
                    }
                }
            }
        });
    }

    /**
     * الحصول على اللجنة المرتبطة بالطلب
     */
    protected function getCommitteeForRequest(QualificationRequest $request): ?Committee
    {
        $activity = $request->company->activities()->first();
        if (!$activity) {
            return null;
        }

        $qualificationCommitteeType = \App\Models\CommitteeType::where('name', 'Qualification')->first();
        if (!$qualificationCommitteeType) {
            return null;
        }

        return Committee::where('committee_type_id', $qualificationCommitteeType->id)
            ->where('is_active', true)
            ->first();
    }

    /**
     * إرسال إشعار لعضو في مرحلة معينة
     */
    protected function notifyMemberForStage(QualificationRequest $request, MemberType $memberType): void
    {
        $committee = $this->getCommitteeForRequest($request);
        if (!$committee) {
            return;
        }

        $member = $committee->members()->where('member_type', $memberType)->first();
        if ($member && $member->user) {
            $member->user->notify(new InitialApprovalNotification($request, $member->user));
        }
    }

    /**
     * إرسال إشعار لرئيس اللجنة
     */
    protected function notifyChairman(QualificationRequest $request): void
    {
        $committee = $this->getCommitteeForRequest($request);
        if (!$committee || !$committee->chairman_id) {
            return;
        }

        $chairman = \App\Models\User::find($committee->chairman_id);
        if ($chairman) {
            $chairman->notify(new InitialApprovalNotification($request, $chairman));
        }
    }
}
