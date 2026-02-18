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
                    ->getOptionLabelFromRecordUsing(fn ($record) => __("roles.{$record->name}"))
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
                    ->searchable()
                    ->preload()
                    ->visible(function (callable $get): bool {
                        $roles = $get('roles');
                        if (!is_array($roles) || empty($roles)) {
                            return false;
                        }
                        $committeeMemberId = Role::where('name', 'Committee Member')->first()?->id;
                        return $committeeMemberId && in_array($committeeMemberId, $roles);
                    })
                    ->required(function (callable $get): bool {
                        $roles = $get('roles');
                        if (!is_array($roles) || empty($roles)) {
                            return false;
                        }
                        $committeeMemberId = Role::where('name', 'Committee Member')->first()?->id;
                        return $committeeMemberId && in_array($committeeMemberId, $roles);
                    })
                    ->helperText('اختر دور عضو اللجنة أولاً. التخصص: قانوني، فني، أو مالي')
                    ->columnSpanFull(),
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
                    ->formatStateUsing(function ($state): string {
                        if (is_iterable($state) && !is_string($state)) {
                            return collect($state)->map(fn ($n) => __("roles.{$n}"))->implode(', ');
                        }
                        $key = "roles.{$state}";
                        $t = __($key);
                        return $t === $key ? (string) ($state ?? '') : $t;
                    })
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
                Tables\Columns\TextColumn::make('committees.name')
                    ->label('اللجنة')
                    ->formatStateUsing(fn ($state): string => is_iterable($state)
                        ? collect($state)->implode(', ')
                        : (string) ($state ?? '—')),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاريخ الإنشاء')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('committee')
                    ->label('اللجنة')
                    ->relationship('committees', 'name')
                    ->searchable()
                    ->preload()
                    ->query(fn ($query, array $data) => !empty($data['committee'])
                        ? $query->whereHas('committees', fn ($q) => $q->where('committees.id', $data['committee']))
                        : $query),
                Tables\Filters\SelectFilter::make('role')
                    ->label('الدور')
                    ->relationship('roles', 'name')
                    ->searchable()
                    ->preload()
                    ->getOptionLabelFromRecordUsing(fn ($record) => __("roles.{$record->name}")),
                Tables\Filters\SelectFilter::make('member_type')
                    ->label('نوع العضو')
                    ->options([
                        MemberType::Legal->value => 'عضو قانوني',
                        MemberType::Technical->value => 'عضو فني',
                        MemberType::Financial->value => 'عضو مالي',
                    ]),
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

