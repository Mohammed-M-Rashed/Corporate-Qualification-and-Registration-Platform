<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CompanyResource\Pages;
use App\Filament\Resources\CompanyResource\RelationManagers;
use App\Models\Company;
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

class CompanyResource extends Resource
{
    protected static ?string $model = Company::class;

    protected static ?string $navigationLabel = 'الشركات';

    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-o-building-office-2';
    }

    protected static ?string $modelLabel = 'شركة';

    protected static ?string $pluralModelLabel = 'الشركات';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('اسم الشركة')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('city_id')
                    ->label('المدينة')
                    ->relationship('city', 'name')
                    ->required()
                    ->searchable()
                    ->preload(),
                Forms\Components\TextInput::make('commercial_register_number')
                    ->label('رقم السجل التجاري')
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),
                Forms\Components\DatePicker::make('commercial_register_start_date')
                    ->label('تاريخ بداية السجل التجاري'),
                Forms\Components\DatePicker::make('commercial_register_end_date')
                    ->label('تاريخ نهاية السجل التجاري'),
                Forms\Components\TextInput::make('email')
                    ->label('البريد الإلكتروني')
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),
                Forms\Components\TextInput::make('phone')
                    ->label('رقم الهاتف')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('address')
                    ->label('العنوان')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\Toggle::make('is_agent')
                    ->label('وكيل معتمد')
                    ->default(false),
                Forms\Components\FileUpload::make('agent_document_path')
                    ->label('مستند الوكالة')
                    ->acceptedFileTypes(['application/pdf'])
                    ->directory('agent_documents')
                    ->visibility('private')
                    ->visible(fn ($get) => $get('is_agent')),
                Forms\Components\Toggle::make('is_qualified')
                    ->label('مؤهلة')
                    ->default(false),
                Forms\Components\DatePicker::make('qualification_expiry_date')
                    ->label('تاريخ انتهاء التأهيل'),
                Forms\Components\Toggle::make('is_active')
                    ->label('نشطة')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('اسم الشركة')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('city.name')
                    ->label('المدينة')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_agent')
                    ->label('وكيل معتمد')
                    ->boolean(),
                Tables\Columns\TextColumn::make('commercial_register_number')
                    ->label('رقم السجل التجاري')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('البريد الإلكتروني')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_qualified')
                    ->label('مؤهلة')
                    ->boolean()
                    ->sortable(),
                Tables\Columns\TextColumn::make('qualification_expiry_date')
                    ->label('تاريخ انتهاء التأهيل')
                    ->date()
                    ->sortable()
                    ->badge()
                    ->color(function ($state, $record): string {
                        if (!$state) return 'gray';
                        $now = now();
                        $expiry = \Carbon\Carbon::parse($state);
                        if ($expiry->isPast()) return 'danger';
                        if ($expiry->diffInDays($now) <= 30) return 'warning';
                        return 'success';
                    })
                    ->formatStateUsing(function ($state, $record): string {
                        if (!$state) return 'غير مؤهلة';
                        $expiry = \Carbon\Carbon::parse($state);
                        if ($expiry->isPast()) return 'منتهي';
                        return $expiry->format('Y-m-d');
                    }),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('نشطة')
                    ->boolean()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاريخ الإنشاء')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('city_id')
                    ->label('المدينة')
                    ->relationship('city', 'name')
                    ->searchable()
                    ->preload(),
                Tables\Filters\TernaryFilter::make('is_agent')
                    ->label('وكيل معتمد'),
                Tables\Filters\TernaryFilter::make('is_qualified')
                    ->label('مؤهلة'),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('نشطة'),
            ])
            ->headerActions([
                CreateAction::make()
                    ->slideOver()
                    ->modalWidth('2xl')
                    ->visible(fn () => Auth::user()?->hasRole('Admin') ?? false),
            ])
            ->actions([
                ViewAction::make()
                    ->slideOver()
                    ->modalWidth('2xl'),
                EditAction::make()
                    ->slideOver()
                    ->modalWidth('2xl')
                    ->visible(fn () => Auth::user()?->hasRole('Admin') ?? false),
                Action::make('toggle_active')
                    ->label(fn ($record): string => ($record?->is_active ?? false) ? 'إلغاء التفعيل' : 'تفعيل')
                    ->icon(fn ($record): string => ($record?->is_active ?? false) ? 'heroicon-o-x-circle' : 'heroicon-o-check-circle')
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        if (!$record instanceof Company) {
                            return;
                        }
                        $newStatus = !$record->is_active;
                        $record->update(['is_active' => $newStatus]);
                        \Filament\Notifications\Notification::make()
                            ->title($newStatus ? 'تم تفعيل الشركة' : 'تم إلغاء تفعيل الشركة')
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
                                ->title('تم تحديث حالة الشركات')
                                ->success()
                                ->send();
                        }),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\QualificationRequestsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCompanies::route('/'),
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        $user = Auth::user();
        if (!$user) {
            return false;
        }
        
        // Admin، رئيس اللجنة، وعضو اللجنة يرون الشركات
        return $user->hasAnyRole(['Admin', 'Chairman', 'Committee Member']);
    }
}

