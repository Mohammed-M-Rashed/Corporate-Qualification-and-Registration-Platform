<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CommitteeResource\Pages;
use App\Filament\Resources\CommitteeResource\RelationManagers;
use App\Models\Committee;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
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

class CommitteeResource extends Resource
{
    protected static ?string $model = Committee::class;

    protected static ?string $navigationLabel = 'اللجان';

    protected static ?string $modelLabel = 'لجنة';

    protected static ?string $pluralModelLabel = 'اللجان';

    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-o-user-group';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\Select::make('committee_type_id')
                    ->label('نوع اللجنة')
                    ->relationship('type', 'name_ar', fn ($query) => $query->where('is_active', true))
                    ->required()
                    ->searchable()
                    ->preload(),
                Forms\Components\TextInput::make('name')
                    ->label('اسم اللجنة')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('chairman_id')
                    ->label('رئيس اللجنة')
                    ->relationship('chairman', 'name')
                    ->searchable()
                    ->preload(),
                Forms\Components\Select::make('members')
                    ->label('أعضاء اللجنة')
                    ->relationship('members', 'name')
                    ->multiple()
                    ->searchable()
                    ->preload()
                    ->columnSpanFull(),
                Forms\Components\FileUpload::make('establishment_decision_file')
                    ->label('قرار التأسيس')
                    ->acceptedFileTypes(['application/pdf'])
                    ->directory('committee_decisions')
                    ->columnSpanFull(),
                Forms\Components\DatePicker::make('start_date')
                    ->label('تاريخ البداية')
                    ->required(),
                Forms\Components\DatePicker::make('end_date')
                    ->label('تاريخ النهاية')
                    ->required()
                    ->after('start_date'),
                Forms\Components\Toggle::make('is_active')
                    ->label('مفعّلة')
                    ->default(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('اسم اللجنة')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('type.name_ar')
                    ->label('نوع اللجنة')
                    ->sortable(),
                Tables\Columns\TextColumn::make('chairman.name')
                    ->label('رئيس اللجنة')
                    ->searchable(),
                Tables\Columns\TextColumn::make('members_count')
                    ->label('عدد الأعضاء')
                    ->counts('members')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('مفعّلة')
                    ->boolean()
                    ->sortable(),
                Tables\Columns\TextColumn::make('start_date')
                    ->label('تاريخ البداية')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_date')
                    ->label('تاريخ النهاية')
                    ->date()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('مفعّلة'),
            ])
            ->actions([
                ViewAction::make()
                    ->slideOver()
                    ->modalWidth('2xl'),
                EditAction::make()
                    ->slideOver()
                    ->modalWidth('2xl'),
                Action::make('toggle_active')
                    ->label(fn (Committee $record): string => $record->is_active ? 'إلغاء التفعيل' : 'تفعيل')
                    ->icon(fn (Committee $record): string => $record->is_active ? 'heroicon-o-x-circle' : 'heroicon-o-check-circle')
                    ->color(fn (Committee $record): string => $record->is_active ? 'danger' : 'success')
                    ->requiresConfirmation()
                    ->action(function (Committee $record) {
                        $newStatus = !$record->is_active;
                        $record->update(['is_active' => $newStatus]);
                        \Filament\Notifications\Notification::make()
                            ->title($newStatus ? 'تم تفعيل اللجنة' : 'تم إلغاء تفعيل اللجنة')
                            ->success()
                            ->send();
                    }),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    BulkAction::make('toggle_active')
                        ->label('تفعيل/إلغاء التفعيل')
                        ->icon('heroicon-o-arrow-path')
                        ->requiresConfirmation()
                        ->action(function ($records) {
                            foreach ($records as $record) {
                                $record->update(['is_active' => !$record->is_active]);
                            }
                            \Filament\Notifications\Notification::make()
                                ->title('تم تحديث حالة اللجان')
                                ->success()
                                ->send();
                        }),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\CommitteeMembersRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCommittees::route('/'),
            'create' => Pages\CreateCommittee::route('/create'),
            'edit' => Pages\EditCommittee::route('/{record}/edit'),
            'view' => Pages\ViewCommittee::route('/{record}'),
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        $user = Auth::user();
        if (!$user) {
            return false;
        }
        
        // فقط Admin يرى اللجان
        return $user->hasRole('Admin');
    }
}
