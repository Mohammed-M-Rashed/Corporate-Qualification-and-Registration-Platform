<?php

namespace App\Filament\Resources;

use App\Filament\Resources\QualificationRequestResource\Pages;
use App\Filament\Resources\QualificationRequestResource\RelationManagers;
use App\Models\QualificationRequest;
use App\Models\Committee;
use App\Models\CommitteeMember;
use App\Enums\QualificationRequestStatus;
use App\Enums\ReviewStage;
use App\Enums\MemberType;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class QualificationRequestResource extends Resource
{
    protected static ?string $model = QualificationRequest::class;

    protected static ?string $navigationLabel = 'طلبات التأهيل';

    protected static ?string $modelLabel = 'طلب تأهيل';

    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-o-clipboard-document-check';
    }

    protected static ?string $pluralModelLabel = 'طلبات التأهيل';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                \Filament\Schemas\Components\Section::make('معلومات الطلب')
                    ->schema([
                        Forms\Components\Select::make('company_id')
                            ->label('الشركة')
                            ->relationship('company', 'name')
                            ->required()
                            ->searchable(),
                        Forms\Components\TextInput::make('request_number')
                            ->label('رقم الطلب')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        Forms\Components\Select::make('status')
                            ->label('الحالة')
                            ->options([
                                QualificationRequestStatus::New->value => 'جديد',
                                QualificationRequestStatus::UnderReview->value => 'قيد المراجعة',
                                QualificationRequestStatus::Approved->value => 'مقبول',
                                QualificationRequestStatus::Rejected->value => 'مرفوض',
                            ])
                            ->required(),
                    ]),
                \Filament\Schemas\Components\Section::make('بيانات الشركة')
                    ->schema([
                        Forms\Components\TextInput::make('company_name')
                            ->label('اسم الشركة')
                            ->default(fn ($record) => $record?->company?->name)
                            ->disabled()
                            ->dehydrated(false),
                        Forms\Components\TextInput::make('company_city')
                            ->label('المدينة')
                            ->default(fn ($record) => $record?->company?->city?->name)
                            ->disabled()
                            ->dehydrated(false),
                        Forms\Components\TextInput::make('company_commercial_register_number')
                            ->label('رقم السجل التجاري')
                            ->default(fn ($record) => $record?->company?->commercial_register_number)
                            ->disabled()
                            ->dehydrated(false),
                        Forms\Components\TextInput::make('company_email')
                            ->label('البريد الإلكتروني')
                            ->default(fn ($record) => $record?->company?->email)
                            ->disabled()
                            ->dehydrated(false),
                        Forms\Components\TextInput::make('company_phone')
                            ->label('رقم الهاتف')
                            ->default(fn ($record) => $record?->company?->phone)
                            ->disabled()
                            ->dehydrated(false),
                        Forms\Components\Textarea::make('company_address')
                            ->label('العنوان')
                            ->default(fn ($record) => $record?->company?->address)
                            ->disabled()
                            ->dehydrated(false)
                            ->columnSpanFull(),
                        Forms\Components\DatePicker::make('company_commercial_register_start_date')
                            ->label('تاريخ بداية السجل التجاري')
                            ->default(fn ($record) => $record?->company?->commercial_register_start_date)
                            ->disabled()
                            ->dehydrated(false),
                        Forms\Components\DatePicker::make('company_commercial_register_end_date')
                            ->label('تاريخ نهاية السجل التجاري')
                            ->default(fn ($record) => $record?->company?->commercial_register_end_date)
                            ->disabled()
                            ->dehydrated(false),
                        Forms\Components\Placeholder::make('company_agent_document')
                            ->label('ملف الوكالة')
                            ->content(function ($record) {
                                $company = $record?->company;
                                if (!$company || !$company->is_agent || !$company->agent_document_path) {
                                    return new \Illuminate\Support\HtmlString('<span class="text-gray-500">— لا يوجد</span>');
                                }
                                $url = route('qualification-request.agent-document', ['qualificationRequest' => $record->id]);
                                $name = basename(str_replace('\\', '/', $company->agent_document_path));
                                return new \Illuminate\Support\HtmlString(
                                    '<a href="' . e($url) . '" target="_blank" rel="noopener" class="text-primary-600 hover:underline inline-flex items-center gap-1">' .
                                    '<svg style="width:12px;height:12px;vertical-align:middle;display:inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>' .
                                    e($name) . ' (PDF)' .
                                    '</a>'
                                );
                            })
                            ->visible(fn ($record) => $record && $record->company)
                            ->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->visible(fn ($record) => $record && $record->company),
                \Filament\Schemas\Components\Section::make('المراجعة القانونية')
                    ->columnSpanFull()
                    ->schema([
                        Forms\Components\Placeholder::make('legal_reviewer_name')
                            ->label('اسم العضو القانوني')
                            ->content(fn ($record) => $record?->legalReviewedBy?->name ?? 'لم يتم التعيين بعد'),
                        Forms\Components\Placeholder::make('legal_review_status_text')
                            ->label('حالة المراجعة القانونية')
                            ->content(function ($record) {
                                if (!$record || !$record->legal_review_status) {
                                    return new \Illuminate\Support\HtmlString('<span style="color: #6b7280;">لم يتم المراجعة بعد</span>');
                                }
                                $statusText = match($record->legal_review_status->value) {
                                    'approved' => 'مقبول ✓',
                                    'rejected' => 'مرفوض ✗',
                                    default => 'قيد المراجعة'
                                };
                                $color = match($record->legal_review_status->value) {
                                    'approved' => '#15803d',
                                    'rejected' => '#dc2626',
                                    default => '#6b7280'
                                };
                                return new \Illuminate\Support\HtmlString("<span style='color: {$color}; font-weight: bold;'>{$statusText}</span>");
                            }),
                        Forms\Components\Placeholder::make('legal_reviewed_at_display')
                            ->label('تاريخ المراجعة القانونية')
                            ->content(fn ($record) => $record?->legal_reviewed_at?->format('Y-m-d H:i') ?? 'غير متاح')
                            ->visible(fn ($record) => $record && $record->legal_reviewed_at),
                        Forms\Components\Placeholder::make('legal_rejection_reasons_display')
                            ->label('أسباب الرفض القانونية')
                            ->content(function ($record) {
                                if (!$record) return 'غير متاح';
                                $review = $record->committeeReviews()
                                    ->where('member_type', \App\Enums\MemberType::Legal)
                                    ->first();
                                if (!$review || !$review->rejection_reason_ids) return 'لا يوجد';
                                $reasons = \App\Models\RejectionReason::whereIn('id', $review->rejection_reason_ids)->get();
                                $reasonsList = $reasons->pluck('reason')->implode('<br>• ');
                                return new \Illuminate\Support\HtmlString("• " . $reasonsList);
                            })
                            ->visible(function ($record) {
                                if (!$record) return false;
                                $review = $record->committeeReviews()
                                    ->where('member_type', \App\Enums\MemberType::Legal)
                                    ->first();
                                return $review && $review->rejection_reason_ids && count($review->rejection_reason_ids) > 0;
                            }),
                    ])
                    ->columns(2)
                    ->visible(function ($record) {
                        if (!$record) return false;
                        $user = Auth::user();
                        
                        // رئيس اللجنة: يرى جميع الأقسام عندما يكون في مرحلته أو مكتمل
                        $isChairman = Committee::where('chairman_id', $user->id)
                            ->where('is_active', true)
                            ->exists();
                        if ($isChairman && in_array($record->current_review_stage, [ReviewStage::Chairman, ReviewStage::Completed])) {
                            return true;
                        }
                        
                        // العضو القانوني: يرى فقط القسم القانوني
                        $committeeMember = CommitteeMember::where('user_id', $user->id)
                            ->whereHas('committee', fn($q) => $q->where('is_active', true))
                            ->first();
                        if ($committeeMember && $committeeMember->member_type === MemberType::Legal) {
                            return true;
                        }
                        
                        return false;
                    }),
                \Filament\Schemas\Components\Section::make('المراجعة الفنية')
                    ->columnSpanFull()
                    ->schema([
                        Forms\Components\Placeholder::make('technical_reviewer_name')
                            ->label('اسم العضو الفني')
                            ->content(fn ($record) => $record?->technicalReviewedBy?->name ?? 'لم يتم التعيين بعد'),
                        Forms\Components\Placeholder::make('technical_review_status_text')
                            ->label('حالة المراجعة الفنية')
                            ->content(function ($record) {
                                if (!$record || !$record->technical_review_status) {
                                    return new \Illuminate\Support\HtmlString('<span style="color: #6b7280;">لم يتم المراجعة بعد</span>');
                                }
                                $statusText = match($record->technical_review_status->value) {
                                    'approved' => 'مقبول ✓',
                                    'rejected' => 'مرفوض ✗',
                                    default => 'قيد المراجعة'
                                };
                                $color = match($record->technical_review_status->value) {
                                    'approved' => '#15803d',
                                    'rejected' => '#dc2626',
                                    default => '#6b7280'
                                };
                                return new \Illuminate\Support\HtmlString("<span style='color: {$color}; font-weight: bold;'>{$statusText}</span>");
                            }),
                        Forms\Components\Placeholder::make('technical_reviewed_at_display')
                            ->label('تاريخ المراجعة الفنية')
                            ->content(fn ($record) => $record?->technical_reviewed_at?->format('Y-m-d H:i') ?? 'غير متاح')
                            ->visible(fn ($record) => $record && $record->technical_reviewed_at),
                        Forms\Components\Placeholder::make('technical_rejection_reasons_display')
                            ->label('أسباب الرفض الفنية')
                            ->content(function ($record) {
                                if (!$record) return 'غير متاح';
                                $review = $record->committeeReviews()
                                    ->where('member_type', \App\Enums\MemberType::Technical)
                                    ->first();
                                if (!$review || !$review->rejection_reason_ids) return 'لا يوجد';
                                $reasons = \App\Models\RejectionReason::whereIn('id', $review->rejection_reason_ids)->get();
                                $reasonsList = $reasons->pluck('reason')->implode('<br>• ');
                                return new \Illuminate\Support\HtmlString("• " . $reasonsList);
                            })
                            ->visible(function ($record) {
                                if (!$record) return false;
                                $review = $record->committeeReviews()
                                    ->where('member_type', \App\Enums\MemberType::Technical)
                                    ->first();
                                return $review && $review->rejection_reason_ids && count($review->rejection_reason_ids) > 0;
                            }),
                    ])
                    ->columns(2)
                    ->visible(function ($record) {
                        if (!$record) return false;
                        $user = Auth::user();
                        
                        // رئيس اللجنة: يرى جميع الأقسام عندما يكون في مرحلته أو مكتمل
                        $isChairman = Committee::where('chairman_id', $user->id)
                            ->where('is_active', true)
                            ->exists();
                        if ($isChairman && in_array($record->current_review_stage, [ReviewStage::Chairman, ReviewStage::Completed])) {
                            return true;
                        }
                        
                        // العضو الفني: يرى فقط القسم الفني
                        $committeeMember = CommitteeMember::where('user_id', $user->id)
                            ->whereHas('committee', fn($q) => $q->where('is_active', true))
                            ->first();
                        if ($committeeMember && $committeeMember->member_type === MemberType::Technical) {
                            return true;
                        }
                        
                        return false;
                    }),
                \Filament\Schemas\Components\Section::make('المراجعة المالية')
                    ->columnSpanFull()
                    ->schema([
                        Forms\Components\Placeholder::make('financial_reviewer_name')
                            ->label('اسم العضو المالي')
                            ->content(fn ($record) => $record?->financialReviewedBy?->name ?? 'لم يتم التعيين بعد'),
                        Forms\Components\Placeholder::make('financial_review_status_text')
                            ->label('حالة المراجعة المالية')
                            ->content(function ($record) {
                                if (!$record || !$record->financial_review_status) {
                                    return new \Illuminate\Support\HtmlString('<span style="color: #6b7280;">لم يتم المراجعة بعد</span>');
                                }
                                $statusText = match($record->financial_review_status->value) {
                                    'approved' => 'مقبول ✓',
                                    'rejected' => 'مرفوض ✗',
                                    default => 'قيد المراجعة'
                                };
                                $color = match($record->financial_review_status->value) {
                                    'approved' => '#15803d',
                                    'rejected' => '#dc2626',
                                    default => '#6b7280'
                                };
                                return new \Illuminate\Support\HtmlString("<span style='color: {$color}; font-weight: bold;'>{$statusText}</span>");
                            }),
                        Forms\Components\Placeholder::make('financial_reviewed_at_display')
                            ->label('تاريخ المراجعة المالية')
                            ->content(fn ($record) => $record?->financial_reviewed_at?->format('Y-m-d H:i') ?? 'غير متاح')
                            ->visible(fn ($record) => $record && $record->financial_reviewed_at),
                        Forms\Components\Placeholder::make('financial_rejection_reasons_display')
                            ->label('أسباب الرفض المالية')
                            ->content(function ($record) {
                                if (!$record) return 'غير متاح';
                                $review = $record->committeeReviews()
                                    ->where('member_type', \App\Enums\MemberType::Financial)
                                    ->first();
                                if (!$review || !$review->rejection_reason_ids) return 'لا يوجد';
                                $reasons = \App\Models\RejectionReason::whereIn('id', $review->rejection_reason_ids)->get();
                                $reasonsList = $reasons->pluck('reason')->implode('<br>• ');
                                return new \Illuminate\Support\HtmlString("• " . $reasonsList);
                            })
                            ->visible(function ($record) {
                                if (!$record) return false;
                                $review = $record->committeeReviews()
                                    ->where('member_type', \App\Enums\MemberType::Financial)
                                    ->first();
                                return $review && $review->rejection_reason_ids && count($review->rejection_reason_ids) > 0;
                            }),
                    ])
                    ->columns(2)
                    ->visible(function ($record) {
                        if (!$record) return false;
                        $user = Auth::user();
                        
                        // رئيس اللجنة: يرى جميع الأقسام عندما يكون في مرحلته أو مكتمل
                        $isChairman = Committee::where('chairman_id', $user->id)
                            ->where('is_active', true)
                            ->exists();
                        if ($isChairman && in_array($record->current_review_stage, [ReviewStage::Chairman, ReviewStage::Completed])) {
                            return true;
                        }
                        
                        // العضو المالي: يرى فقط القسم المالي
                        $committeeMember = CommitteeMember::where('user_id', $user->id)
                            ->whereHas('committee', fn($q) => $q->where('is_active', true))
                            ->first();
                        if ($committeeMember && $committeeMember->member_type === MemberType::Financial) {
                            return true;
                        }
                        
                        return false;
                    }),
                \Filament\Schemas\Components\Section::make('مراجعة رئيس اللجنة')
                    ->columnSpanFull()
                    ->schema([
                        Forms\Components\Placeholder::make('chairman_reviewer_name')
                            ->label('اسم رئيس اللجنة')
                            ->content(function ($record) {
                                if (!$record) return 'غير متاح';
                                $committee = Committee::where('is_active', true)->first();
                                if (!$committee || !$committee->chairman_id) return 'لم يتم التعيين بعد';
                                $chairman = \App\Models\User::find($committee->chairman_id);
                                return $chairman?->name ?? 'لم يتم التعيين بعد';
                            }),
                        Forms\Components\Placeholder::make('chairman_review_status_text')
                            ->label('القرار النهائي')
                            ->content(function ($record) {
                                if (!$record || $record->current_review_stage !== ReviewStage::Completed) {
                                    return new \Illuminate\Support\HtmlString('<span style="color: #6b7280;">في انتظار القرار النهائي</span>');
                                }
                                $statusText = match($record->status->value) {
                                    'approved' => 'مقبول نهائياً ✓',
                                    'rejected' => 'مرفوض نهائياً ✗',
                                    default => 'قيد المراجعة'
                                };
                                $color = match($record->status->value) {
                                    'approved' => '#15803d',
                                    'rejected' => '#dc2626',
                                    default => '#6b7280'
                                };
                                return new \Illuminate\Support\HtmlString("<span style='color: {$color}; font-weight: bold;'>{$statusText}</span>");
                            }),
                        Forms\Components\Placeholder::make('chairman_reviewed_at_display')
                            ->label('تاريخ القرار النهائي')
                            ->content(fn ($record) => $record?->updated_at?->format('Y-m-d H:i') ?? 'غير متاح')
                            ->visible(fn ($record) => $record && $record->current_review_stage === ReviewStage::Completed),
                        Forms\Components\Placeholder::make('chairman_notes_display')
                            ->label('ملاحظات رئيس اللجنة')
                            ->content(function ($record) {
                                if (!$record) return 'غير متاح';
                                $latestAction = $record->requestActions()
                                    ->whereIn('action_type', [\App\Enums\RequestActionType::FinalApproval, \App\Enums\RequestActionType::FinalRejection])
                                    ->latest()
                                    ->first();
                                return $latestAction?->notes ?? 'لا توجد ملاحظات';
                            })
                            ->visible(fn ($record) => $record && $record->current_review_stage === ReviewStage::Completed),
                    ])
                    ->columns(2)
                    ->visible(function ($record) {
                        if (!$record) return false;
                        $user = Auth::user();
                        
                        // يظهر لرئيس اللجنة دائماً
                        $isChairman = Committee::where('chairman_id', $user->id)
                            ->where('is_active', true)
                            ->exists();
                        if ($isChairman) {
                            return true;
                        }
                        
                        // يظهر لجميع أعضاء اللجنة
                        $isCommitteeMember = CommitteeMember::where('user_id', $user->id)
                            ->whereHas('committee', fn($q) => $q->where('is_active', true))
                            ->exists();
                        if ($isCommitteeMember) {
                            return true;
                        }
                        
                        return false;
                    }),
            ]);
    }

    public static function table(Table $table): Table
    {
        $user = Auth::user();
        $isAdmin = $user->hasRole('Admin');
        $isCommitteeMember = $user->hasRole('Committee Member');
        // التحقق من أن المستخدم هو رئيس لجنة نشطة
        $isChairman = Committee::where('chairman_id', $user->id)
            ->where('is_active', true)
            ->exists();

        // Auto-filter based on user role and member type
        $query = QualificationRequest::query()->with(['company']);
        
        if (!$isAdmin) {
            if ($isChairman) {
                // رئيس اللجنة: يرى الطلبات في مرحلة chairman والطلبات المكتملة
                $query->whereIn('current_review_stage', [ReviewStage::Chairman, ReviewStage::Completed]);
            }
            // أعضاء اللجنة (قانوني، فني، مالي) يروا جميع الطلبات بدون فلترة
        }

        return $table
            ->query($query)
            ->columns([
                Tables\Columns\TextColumn::make('request_number')
                    ->label('رقم الطلب')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('company.name')
                    ->label('اسم الشركة')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('current_review_stage')
                    ->label('المرحلة الحالية')
                    ->badge()
                    ->color(fn ($state): string => match ($state) {
                        ReviewStage::Legal => 'blue',
                        ReviewStage::Technical => 'purple',
                        ReviewStage::Financial => 'green',
                        ReviewStage::Chairman => 'orange',
                        ReviewStage::Completed => 'gray',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn ($state): string => match ($state) {
                        ReviewStage::Legal => 'قانوني',
                        ReviewStage::Technical => 'فني',
                        ReviewStage::Financial => 'مالي',
                        ReviewStage::Chairman => 'رئيس اللجنة',
                        ReviewStage::Completed => 'مكتمل',
                        default => (string) $state,
                    }),
                Tables\Columns\TextColumn::make('status')
                    ->label('الحالة')
                    ->badge()
                    ->color(fn ($state): string => match ($state) {
                        QualificationRequestStatus::New => 'gray',
                        QualificationRequestStatus::UnderReview => 'warning',
                        QualificationRequestStatus::Approved => 'success',
                        QualificationRequestStatus::Rejected => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn ($state): string => match ($state) {
                        QualificationRequestStatus::New => 'جديد',
                        QualificationRequestStatus::UnderReview => 'قيد المراجعة',
                        QualificationRequestStatus::Approved => 'مقبول',
                        QualificationRequestStatus::Rejected => 'مرفوض',
                        default => (string) $state,
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاريخ الإنشاء')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('company_id')
                    ->label('الشركة')
                    ->relationship('company', 'name')
                    ->searchable()
                    ->preload()
                    ->visible(fn () => $isAdmin),
                Tables\Filters\SelectFilter::make('current_review_stage')
                    ->label('المرحلة الحالية')
                    ->options([
                        ReviewStage::Legal->value => 'قانوني',
                        ReviewStage::Technical->value => 'فني',
                        ReviewStage::Financial->value => 'مالي',
                        ReviewStage::Chairman->value => 'رئيس اللجنة',
                        ReviewStage::Completed->value => 'مكتمل',
                    ]),
                Tables\Filters\SelectFilter::make('status')
                    ->label('الحالة')
                    ->options(function () use ($isAdmin, $isChairman, $isCommitteeMember) {
                        $options = [];
                        
                        if ($isAdmin) {
                            $options = [
                                QualificationRequestStatus::New->value => 'جديد',
                                QualificationRequestStatus::UnderReview->value => 'قيد المراجعة',
                                QualificationRequestStatus::Approved->value => 'مقبول',
                                QualificationRequestStatus::Rejected->value => 'مرفوض',
                            ];
                        } elseif ($isChairman) {
                            $options = [
                                QualificationRequestStatus::UnderReview->value => 'قيد المراجعة',
                                QualificationRequestStatus::Approved->value => 'مقبول',
                                QualificationRequestStatus::Rejected->value => 'مرفوض',
                            ];
                        } elseif ($isCommitteeMember) {
                            $options = [
                                QualificationRequestStatus::New->value => 'جديد',
                                QualificationRequestStatus::Rejected->value => 'مرفوض',
                                QualificationRequestStatus::UnderReview->value => 'قيد المراجعة',
                            ];
                        }
                        
                        return $options;
                    }),
            ])
            ->headerActions([
                CreateAction::make()
                    ->slideOver()
                    ->modalWidth('2xl'),
            ])
            ->actions([
                ViewAction::make(),
                // لا يمكن التعديل على الطلبات، فقط تغيير الحالة
                // EditAction::make()
                //     ->slideOver()
                //     ->modalWidth('2xl'),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    // No bulk actions for qualification requests
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\LegalDocumentsRelationManager::class,
            RelationManagers\TechnicalDocumentsRelationManager::class,
            RelationManagers\FinancialDocumentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListQualificationRequests::route('/'),
            'view' => Pages\ViewQualificationRequest::route('/{record}'),
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        $user = Auth::user();
        if (!$user) {
            return false;
        }
        
        // Admin، رئيس اللجنة، وعضو اللجنة يرون طلبات التأهيل
        return $user->hasAnyRole(['Admin', 'Chairman', 'Committee Member']);
    }

    protected static function getUserMemberType(): ?MemberType
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
}

