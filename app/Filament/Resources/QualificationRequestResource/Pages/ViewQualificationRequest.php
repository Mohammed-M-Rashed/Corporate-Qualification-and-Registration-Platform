<?php

namespace App\Filament\Resources\QualificationRequestResource\Pages;

use App\Filament\Resources\QualificationRequestResource;
use App\Services\QualificationWorkflowService;
use App\Enums\QualificationRequestStatus;
use App\Enums\ReviewStage;
use App\Enums\MemberType;
use App\Models\Committee;
use App\Models\CommitteeMember;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Facades\Auth;

class ViewQualificationRequest extends ViewRecord
{
    protected static string $resource = QualificationRequestResource::class;

    protected function getUserMemberType(): ?MemberType
    {
        $user = Auth::user();
        $qualificationCommitteeType = \App\Models\CommitteeType::where('name', 'Qualification')->first();
        if (!$qualificationCommitteeType) {
            return null;
        }

        $activeCommittee = Committee::where('committee_type_id', $qualificationCommitteeType->id)
            ->where('is_active', true)
            ->first();

        if (!$activeCommittee) {
            return null;
        }

        $member = CommitteeMember::where('committee_id', $activeCommittee->id)
            ->where('user_id', $user->id)
            ->first();

        return $member?->member_type;
    }

    public function mount(int | string $record): void
    {
        parent::mount($record);
        
        // Load all necessary relationships
        $this->record->load([
            'company.city',
            'legalReviewedBy',
            'technicalReviewedBy',
            'financialReviewedBy',
            'rejectionReasons',
            'committeeReviews'
        ]);
        
        // Fill form data including company data
        if ($this->record->company) {
            $this->form->fill([
                'company_id' => $this->record->company_id,
                'request_number' => $this->record->request_number,
                'status' => $this->record->status->value,
                'company_name' => $this->record->company->name,
                'company_city' => $this->record->company->city?->name,
                'company_commercial_register_number' => $this->record->company->commercial_register_number,
                'company_email' => $this->record->company->email,
                'company_phone' => $this->record->company->phone,
                'company_address' => $this->record->company->address,
                'company_commercial_register_start_date' => $this->record->company->commercial_register_start_date,
                'company_commercial_register_end_date' => $this->record->company->commercial_register_end_date,
            ]);
        } else {
            $this->form->fill([
                'company_id' => $this->record->company_id,
                'request_number' => $this->record->request_number,
                'status' => $this->record->status->value,
            ]);
        }
    }

    protected function getHeaderActions(): array
    {
        $user = Auth::user();
        $isAdmin = $user->hasRole('Admin');
        $isCommitteeMember = $user->hasRole('Committee Member');
        $isChairman = Committee::where('chairman_id', $user->id)
            ->where('is_active', true)
            ->exists();
        
        $memberType = $this->getUserMemberType();
        $workflowService = app(QualificationWorkflowService::class);
        $actions = [];

        // للعضو القانوني: عرض الطلبات في مرحلة legal فقط
        if ($memberType === MemberType::Legal && $this->record->current_review_stage === ReviewStage::Legal) {
            if ($this->record->legal_review_status === null || $this->record->legal_review_status->value === 'pending') {
                $actions[] = Actions\Action::make('legal_approval')
                    ->label('قبول')
                    ->color('success')
                    ->icon('heroicon-o-check-circle')
                    ->requiresConfirmation()
                    ->action(function () use ($workflowService) {
                        $workflowService->legalReview($this->record, Auth::id(), 'approved');
                        $this->refreshFormData(['current_review_stage', 'legal_review_status', 'status']);
                        \Filament\Notifications\Notification::make()
                            ->title('تم قبول الطلب')
                            ->success()
                            ->send();
                        return redirect()->route('filament.admin.resources.qualification-requests.index');
                    });

                $rejectionReasons = \App\Models\RejectionReason::where('is_active', true)->orderBy('order')->get();
                $actions[] = Actions\Action::make('legal_rejection')
                    ->label('رفض')
                    ->color('danger')
                    ->icon('heroicon-o-x-circle')
                    ->form([
                        \Filament\Forms\Components\Select::make('rejection_reason_ids')
                            ->label('أسباب الرفض')
                            ->multiple()
                            ->options($rejectionReasons->pluck('reason', 'id'))
                            ->required()
                            ->searchable(),
                        \Filament\Forms\Components\Textarea::make('notes')
                            ->label('ملاحظات إضافية')
                            ->rows(3),
                    ])
                    ->action(function (array $data) use ($workflowService) {
                        $workflowService->legalReview(
                            $this->record, 
                            Auth::id(), 
                            'rejected', 
                            $data['rejection_reason_ids'] ?? [],
                            $data['notes'] ?? null
                        );
                        $this->refreshFormData(['current_review_stage', 'legal_review_status', 'status']);
                        \Filament\Notifications\Notification::make()
                            ->title('تم رفض الطلب')
                            ->danger()
                            ->send();
                        return redirect()->route('filament.admin.resources.qualification-requests.index');
                    });
            }
        }

        // للعضو الفني: عرض الطلبات في مرحلة technical فقط
        if ($memberType === MemberType::Technical && $this->record->current_review_stage === ReviewStage::Technical) {
            if ($this->record->technical_review_status === null || $this->record->technical_review_status->value === 'pending') {
                $actions[] = Actions\Action::make('technical_approval')
                    ->label('قبول')
                    ->color('success')
                    ->icon('heroicon-o-check-circle')
                    ->requiresConfirmation()
                    ->action(function () use ($workflowService) {
                        $workflowService->technicalReview($this->record, Auth::id(), 'approved');
                        $this->refreshFormData(['current_review_stage', 'technical_review_status', 'status']);
                        \Filament\Notifications\Notification::make()
                            ->title('تم قبول الطلب')
                            ->success()
                            ->send();
                        return redirect()->route('filament.admin.resources.qualification-requests.index');
                    });

                $rejectionReasons = \App\Models\RejectionReason::where('is_active', true)->orderBy('order')->get();
                $actions[] = Actions\Action::make('technical_rejection')
                    ->label('رفض')
                    ->color('danger')
                    ->icon('heroicon-o-x-circle')
                    ->form([
                        \Filament\Forms\Components\Select::make('rejection_reason_ids')
                            ->label('أسباب الرفض')
                            ->multiple()
                            ->options($rejectionReasons->pluck('reason', 'id'))
                            ->required()
                            ->searchable(),
                        \Filament\Forms\Components\Textarea::make('notes')
                            ->label('ملاحظات إضافية')
                            ->rows(3),
                    ])
                    ->action(function (array $data) use ($workflowService) {
                        $workflowService->technicalReview(
                            $this->record, 
                            Auth::id(), 
                            'rejected', 
                            $data['rejection_reason_ids'] ?? [],
                            $data['notes'] ?? null
                        );
                        $this->refreshFormData(['current_review_stage', 'technical_review_status', 'status']);
                        \Filament\Notifications\Notification::make()
                            ->title('تم رفض الطلب')
                            ->danger()
                            ->send();
                        return redirect()->route('filament.admin.resources.qualification-requests.index');
                    });
            }
        }

        // للعضو المالي: عرض الطلبات في مرحلة financial فقط
        if ($memberType === MemberType::Financial && $this->record->current_review_stage === ReviewStage::Financial) {
            if ($this->record->financial_review_status === null || $this->record->financial_review_status->value === 'pending') {
                $actions[] = Actions\Action::make('financial_approval')
                    ->label('قبول')
                    ->color('success')
                    ->icon('heroicon-o-check-circle')
                    ->requiresConfirmation()
                    ->action(function () use ($workflowService) {
                        $workflowService->financialReview($this->record, Auth::id(), 'approved');
                        $this->refreshFormData(['current_review_stage', 'financial_review_status', 'status']);
                        \Filament\Notifications\Notification::make()
                            ->title('تم قبول الطلب')
                            ->success()
                            ->send();
                        return redirect()->route('filament.admin.resources.qualification-requests.index');
                    });

                $rejectionReasons = \App\Models\RejectionReason::where('is_active', true)->orderBy('order')->get();
                $actions[] = Actions\Action::make('financial_rejection')
                    ->label('رفض')
                    ->color('danger')
                    ->icon('heroicon-o-x-circle')
                    ->form([
                        \Filament\Forms\Components\Select::make('rejection_reason_ids')
                            ->label('أسباب الرفض')
                            ->multiple()
                            ->options($rejectionReasons->pluck('reason', 'id'))
                            ->required()
                            ->searchable(),
                        \Filament\Forms\Components\Textarea::make('notes')
                            ->label('ملاحظات إضافية')
                            ->rows(3),
                    ])
                    ->action(function (array $data) use ($workflowService) {
                        $workflowService->financialReview(
                            $this->record, 
                            Auth::id(), 
                            'rejected', 
                            $data['rejection_reason_ids'] ?? [],
                            $data['notes'] ?? null
                        );
                        $this->refreshFormData(['current_review_stage', 'financial_review_status', 'status']);
                        \Filament\Notifications\Notification::make()
                            ->title('تم رفض الطلب')
                            ->danger()
                            ->send();
                        return redirect()->route('filament.admin.resources.qualification-requests.index');
                    });
            }
        }

        // لرئيس اللجنة: عرض الطلبات في مرحلة chairman فقط
        if ($isChairman && $this->record->current_review_stage === ReviewStage::Chairman) {
            $actions[] = Actions\Action::make('final_approval')
                ->label('قبول نهائي')
                ->color('success')
                ->icon('heroicon-o-check-circle')
                ->requiresConfirmation()
                ->action(function () use ($workflowService) {
                    $workflowService->finalApproval($this->record, Auth::id());
                    $this->refreshFormData(['status', 'current_review_stage']);
                    \Filament\Notifications\Notification::make()
                        ->title('تم قبول الطلب نهائياً')
                        ->success()
                        ->send();
                    return redirect()->route('filament.admin.resources.qualification-requests.index');
                });

            $rejectionReasons = \App\Models\RejectionReason::where('is_active', true)->orderBy('order')->get();
            $actions[] = Actions\Action::make('final_rejection')
                ->label('رفض نهائي')
                ->color('danger')
                ->icon('heroicon-o-x-circle')
                ->form([
                    \Filament\Forms\Components\Select::make('rejection_reason_ids')
                        ->label('أسباب الرفض')
                        ->multiple()
                        ->options($rejectionReasons->pluck('reason', 'id'))
                        ->required()
                        ->searchable(),
                    \Filament\Forms\Components\Textarea::make('reason')
                        ->label('ملاحظات إضافية')
                        ->rows(3),
                ])
                ->action(function (array $data) use ($workflowService) {
                    $workflowService->finalRejection(
                        $this->record, 
                        Auth::id(), 
                        $data['rejection_reason_ids'] ?? [],
                        $data['reason'] ?? null
                    );
                    $this->refreshFormData(['status', 'current_review_stage']);
                    \Filament\Notifications\Notification::make()
                        ->title('تم رفض الطلب نهائياً')
                        ->danger()
                        ->send();
                    return redirect()->route('filament.admin.resources.qualification-requests.index');
                });
        }

        return $actions;
    }
}
