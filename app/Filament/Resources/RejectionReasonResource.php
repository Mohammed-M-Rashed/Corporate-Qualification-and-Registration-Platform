<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RejectionReasonResource\Pages;
use App\Models\RejectionReason;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class RejectionReasonResource extends Resource
{
    protected static ?string $model = RejectionReason::class;

    protected static ?string $navigationLabel = 'أسباب الرفض';

    protected static ?string $modelLabel = 'سبب الرفض';

    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-o-x-circle';
    }

    protected static ?string $pluralModelLabel = 'أسباب الرفض';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\TextInput::make('reason')
                    ->label('سبب الرفض')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('order')
                    ->label('الترتيب')
                    ->numeric()
                    ->default(0),
                Forms\Components\Toggle::make('is_active')
                    ->label('نشط')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('reason')
                    ->label('سبب الرفض')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('order')
                    ->label('الترتيب')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('نشط')
                    ->boolean()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاريخ الإنشاء')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('order', 'asc')
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('نشط'),
            ])
            ->headerActions([
                CreateAction::make()
                    ->slideOver()
                    ->modalWidth('2xl'),
            ])
            ->actions([
                EditAction::make()
                    ->slideOver()
                    ->modalWidth('2xl'),
                Action::make('toggle_active')
                    ->label(fn (RejectionReason $record): string => $record->is_active ? 'إلغاء التفعيل' : 'تفعيل')
                    ->icon(fn (RejectionReason $record): string => $record->is_active ? 'heroicon-o-x-circle' : 'heroicon-o-check-circle')
                    ->color(fn (RejectionReason $record): string => $record->is_active ? 'danger' : 'success')
                    ->requiresConfirmation()
                    ->action(function (RejectionReason $record) {
                        $newStatus = !$record->is_active;
                        $record->update(['is_active' => $newStatus]);
                        \Filament\Notifications\Notification::make()
                            ->title($newStatus ? 'تم تفعيل سبب الرفض' : 'تم إلغاء تفعيل سبب الرفض')
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
                                ->title('تم تحديث حالة أسباب الرفض')
                                ->success()
                                ->send();
                        }),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRejectionReasons::route('/'),
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        $user = Auth::user();
        if (!$user) {
            return false;
        }
        
        // فقط Admin يرون أسباب الرفض
        return $user->hasRole('Admin');
    }
}

