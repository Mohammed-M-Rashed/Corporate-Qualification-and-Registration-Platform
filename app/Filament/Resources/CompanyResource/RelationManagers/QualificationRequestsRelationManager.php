<?php

namespace App\Filament\Resources\CompanyResource\RelationManagers;

use App\Enums\QualificationRequestStatus;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class QualificationRequestsRelationManager extends RelationManager
{
    protected static string $relationship = 'qualificationRequests';

    protected static ?string $recordTitleAttribute = 'request_number';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('request_number')
            ->columns([
                Tables\Columns\TextColumn::make('request_number')
                    ->label('رقم الطلب')
                    ->searchable()
                    ->sortable(),
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
                Tables\Filters\SelectFilter::make('status')
                    ->label('الحالة')
                    ->options([
                        QualificationRequestStatus::New->value => 'جديد',
                        QualificationRequestStatus::UnderReview->value => 'قيد المراجعة',
                        QualificationRequestStatus::Approved->value => 'مقبول',
                        QualificationRequestStatus::Rejected->value => 'مرفوض',
                    ]),
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->actions([
                Action::make('view')
                    ->label('عرض')
                    ->url(fn ($record) => route('filament.admin.resources.qualification-requests.view', $record))
                    ->openUrlInNewTab(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}

