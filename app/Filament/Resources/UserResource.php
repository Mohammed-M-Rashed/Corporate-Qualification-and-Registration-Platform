<?php

namespace App\Filament\Resources;

use App\Enums\MemberType;
use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationLabel = 'المستخدمين';

    protected static ?string $modelLabel = 'مستخدم';

    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-o-users';
    }

    protected static ?string $pluralModelLabel = 'المستخدمين';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('الاسم')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->label('البريد الإلكتروني')
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),
                Forms\Components\TextInput::make('password')
                    ->label('كلمة المرور')
                    ->password()
                    ->required(fn ($livewire) => $livewire instanceof \Filament\Resources\Pages\CreateRecord)
                    ->dehydrated(fn ($state) => filled($state))
                    ->maxLength(255),
                Forms\Components\Select::make('roles')
                    ->label('الأدوار')
                    ->relationship('roles', 'name')
                    ->multiple()
                    ->preload()
                    ->live()
                    ->afterStateUpdated(function ($state, callable $set) {
                        // إذا كان الدور Committee Member، نظهر حقل نوع العضو
                        if (!is_array($state) || !in_array('Committee Member', $state)) {
                            $set('member_type', null);
                        }
                    }),
                Forms\Components\Select::make('member_type')
                    ->label('نوع العضو')
                    ->options([
                        MemberType::Legal->value => 'عضو قانوني',
                        MemberType::Technical->value => 'عضو فني',
                        MemberType::Financial->value => 'عضو مالي',
                    ])
                    ->visible(function (callable $get) {
                        $roles = $get('roles');
                        return is_array($roles) && in_array('Committee Member', $roles);
                    })
                    ->required(function (callable $get) {
                        $roles = $get('roles');
                        return is_array($roles) && in_array('Committee Member', $roles);
                    })
                    ->helperText('اختر التخصص الخاص بالعضو (قانوني، فني، أو مالي)')
                    ->columnSpanFull(),
                Forms\Components\Select::make('committee_id')
                    ->label('اللجنة')
                    ->relationship('committee', 'name')
                    ->searchable(),
                Forms\Components\FileUpload::make('work_document')
                    ->label('مستند العمل')
                    ->acceptedFileTypes(['application/pdf'])
                    ->directory('work_documents'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('الاسم')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('البريد الإلكتروني')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('roles.name')
                    ->label('الأدوار')
                    ->badge(),
                Tables\Columns\TextColumn::make('member_type')
                    ->label('نوع العضو')
                    ->badge()
                    ->color(fn ($state): string => match ($state) {
                        MemberType::Legal => 'blue',
                        MemberType::Technical => 'purple',
                        MemberType::Financial => 'green',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn ($state): string => match ($state) {
                        MemberType::Legal => 'عضو قانوني',
                        MemberType::Technical => 'عضو فني',
                        MemberType::Financial => 'عضو مالي',
                        default => '-',
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('committee.name')
                    ->label('اللجنة')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاريخ الإنشاء')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('committee_id')
                    ->label('اللجنة')
                    ->relationship('committee', 'name')
                    ->searchable(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->slideOver()
                    ->modalWidth('2xl'),
            ])
            ->actions([
                ViewAction::make()
                    ->slideOver()
                    ->modalWidth('2xl'),
                EditAction::make()
                    ->slideOver()
                    ->modalWidth('2xl'),
            ])
            ->bulkActions([
                // No bulk actions for users
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
            'index' => Pages\ListUsers::route('/'),
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        $user = Auth::user();
        if (!$user) {
            return false;
        }
        
        // فقط Admin يرى المستخدمين
        return $user->hasRole('Admin');
    }
}

