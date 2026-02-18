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
                Forms\Components\Placeholder::make('establishment_decision_file_view')
                    ->label(function ($record) {
                        if (!$record || empty($record->establishment_decision_file)) {
                            return 'قرار التأسيس';
                        }
                        $path = str_replace('\\', '/', $record->establishment_decision_file);
                        $path = str_starts_with($path, 'committee_decisions/') ? $path : 'committee_decisions/' . ltrim($path, '/');
                        $url = asset('storage/' . $path);
                        $eyeSvg = '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 inline-block align-middle rtl:mr-1 ltr:ml-1" style="vertical-align: middle;"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>';
                        return new \Illuminate\Support\HtmlString(
                            'قرار التأسيس <a href="' . e($url) . '" target="_blank" rel="noopener" class="fi-link text-primary-600 hover:text-primary-500 dark:text-primary-400 dark:hover:text-primary-300 inline-flex items-center align-middle" title="معاينة">' . $eyeSvg . '</a>'
                        );
                    })
                    ->content(function ($record) {
                        if (!$record || empty($record->establishment_decision_file)) {
                            return new \Illuminate\Support\HtmlString('<span class="text-gray-500 dark:text-gray-400">— لا يوجد ملف</span>');
                        }
                        $path = str_replace('\\', '/', $record->establishment_decision_file);
                        $name = basename($path);
                        return new \Illuminate\Support\HtmlString('<span class="text-gray-700 dark:text-gray-300">' . e($name) . '</span>');
                    })
                    ->visible(function (\Livewire\Component $livewire, $record): bool {
                        return $livewire instanceof \App\Filament\Resources\CommitteeResource\Pages\ViewCommittee
                            && (bool) $record
                            && !empty($record->establishment_decision_file);
                    })
                    ->columnSpanFull(),
                Forms\Components\FileUpload::make('establishment_decision_file')
                    ->label(function ($record) {
                        // إضافة أيقونة العين بجانب النص عند وجود ملف
                        if (!$record || empty($record->establishment_decision_file)) {
                            return 'قرار التأسيس';
                        }
                        $path = str_replace('\\', '/', $record->establishment_decision_file);
                        $path = str_starts_with($path, 'committee_decisions/') ? $path : 'committee_decisions/' . ltrim($path, '/');
                        $url = asset('storage/' . $path);
                        $eyeSvg = '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 inline-block align-middle rtl:mr-1 ltr:ml-1" style="vertical-align: middle;"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>';
                        return new \Illuminate\Support\HtmlString(
                            'قرار التأسيس <a href="' . e($url) . '" target="_blank" rel="noopener" class="fi-link text-primary-600 hover:text-primary-500 dark:text-primary-400 dark:hover:text-primary-300 inline-flex items-center align-middle" title="معاينة الملف الحالي">' . $eyeSvg . '</a>'
                        );
                    })
                    ->acceptedFileTypes(['application/pdf'])
                    ->disk('public')
                    ->directory('committee_decisions')
                    ->visibility('public')
                    ->fetchFileInformation(false)
                    ->afterStateHydrated(function (Forms\Components\FileUpload $component, string|array|null $state): void {
                        if (is_string($state) && $state !== '') {
                            $component->rawState([$state]);
                        }
                    })
                    ->getUploadedFileUsing(function ($component, string $file): ?array {
                        $file = str_replace('\\', '/', $file);
                        if (strlen($file) < 3) {
                            return null;
                        }
                        $path = str_starts_with($file, 'committee_decisions/') ? $file : 'committee_decisions/' . ltrim($file, '/');
                        $fullPath = storage_path('app/public/' . $path);
                        $size = 0;
                        $type = 'application/pdf';
                        if (is_file($fullPath)) {
                            $size = (int) filesize($fullPath);
                            $type = mime_content_type($fullPath) ?: $type;
                        }
                        $url = asset('storage/' . $path);
                        return [
                            'name' => basename($path),
                            'size' => $size,
                            'type' => $type,
                            'url' => $url,
                        ];
                    })
                    ->visible(fn (\Livewire\Component $livewire): bool => ! ($livewire instanceof \App\Filament\Resources\CommitteeResource\Pages\ViewCommittee))
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
                    ->default(true)
                    ->reactive(),
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
                Tables\Filters\SelectFilter::make('committee_type_id')
                    ->label('نوع اللجنة')
                    ->relationship('type', 'name_ar')
                    ->searchable()
                    ->preload(),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('مفعّلة'),
            ])
            ->actions([
                Action::make('preview_decision')
                    ->label('معاينة القرار')
                    ->icon('heroicon-o-document-text')
                    ->color('info')
                    ->url(function (Committee $record): ?string {
                        if (!$record->establishment_decision_file) {
                            return null;
                        }
                        $path = str_starts_with($record->establishment_decision_file, 'committee_decisions/')
                            ? $record->establishment_decision_file
                            : 'committee_decisions/' . ltrim($record->establishment_decision_file, '/');
                        return asset('storage/' . $path);
                    })
                    ->openUrlInNewTab()
                    ->visible(fn (Committee $record): bool => !empty($record->establishment_decision_file)),
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
