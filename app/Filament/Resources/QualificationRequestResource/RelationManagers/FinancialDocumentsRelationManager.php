<?php

namespace App\Filament\Resources\QualificationRequestResource\RelationManagers;

use App\Enums\MemberType;
use App\Enums\ReviewStage;
use App\Models\Committee;
use App\Models\CommitteeMember;
use Filament\Actions\Action;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FinancialDocumentsRelationManager extends RelationManager
{
    protected static string $relationship = 'financialDocuments';

    protected static ?string $recordTitleAttribute = 'file_name';

    public static function getTitle(\Illuminate\Database\Eloquent\Model $ownerRecord, string $pageClass): string
    {
        return 'الوثائق المالية';
    }

    public static function canViewForRecord(\Illuminate\Database\Eloquent\Model $ownerRecord, string $pageClass): bool
    {
        $user = Auth::user();
        $isAdmin = $user->hasRole('Admin');
        $isChairman = Committee::where('chairman_id', $user->id)
            ->where('is_active', true)
            ->exists();

        // Admin ورئيس اللجنة يرون جميع الوثائق
        if ($isAdmin || $isChairman) {
            return true;
        }

        // العضو المالي يرى الوثائق المالية فقط
        $memberType = static::getUserMemberType();
        if ($memberType === MemberType::Financial && $ownerRecord->current_review_stage === ReviewStage::Financial) {
            return true;
        }

        return false;
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

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('file_name')
            ->columns([
                Tables\Columns\TextColumn::make('document_type')
                    ->label('نوع الوثيقة')
                    ->formatStateUsing(fn ($state) => $state?->label() ?? $state)
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('file_name')
                    ->label('اسم الملف')
                    ->searchable()
                    ->sortable()
                    ->url(fn ($record) => route('documents.financial.view', $record))
                    ->openUrlInNewTab()
                    ->color('primary')
                    ->icon('heroicon-o-eye')
                    ->iconPosition('after'),
                Tables\Columns\TextColumn::make('file_size')
                    ->label('حجم الملف')
                    ->formatStateUsing(fn ($state) => number_format($state / 1024, 2) . ' KB')
                    ->sortable(),
                Tables\Columns\TextColumn::make('technical_notes')
                    ->label('ملاحظات تقنية')
                    ->limit(50)
                    ->toggleable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاريخ الرفع')
                    ->dateTime()
                    ->sortable(),
            ])
            ->actions([
                Action::make('view')
                    ->label('معاينة')
                    ->icon('heroicon-o-eye')
                    ->color('info')
                    ->url(fn ($record) => route('documents.financial.view', $record))
                    ->openUrlInNewTab(),
                Action::make('download')
                    ->label('تحميل')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->url(fn ($record) => route('documents.financial.download', $record))
                    ->openUrlInNewTab(),
            ]);
    }
}

