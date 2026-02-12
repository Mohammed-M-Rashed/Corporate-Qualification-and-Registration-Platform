<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SettingResource\Pages;
use App\Models\Setting;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class SettingResource extends Resource
{
    protected static ?string $model = Setting::class;

    protected static ?string $navigationLabel = 'الإعدادات';

    protected static ?string $modelLabel = 'إعداد';

    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-o-cog-6-tooth';
    }

    protected static ?string $pluralModelLabel = 'الإعدادات';

    public static function shouldRegisterNavigation(): bool
    {
        $user = auth()->user();
        if (!$user) {
            return false;
        }
        
        // فقط Admin يرون الإعدادات
        // التحقق من أن المستخدم لديه دور Admin فقط وليس لديه أدوار أخرى
        $userRoles = $user->getRoleNames()->toArray();
        return in_array('Admin', $userRoles) && count($userRoles) === 1;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\TextInput::make('key')
                    ->label('المفتاح')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),
                Forms\Components\Textarea::make('value')
                    ->label('القيمة')
                    ->columnSpanFull(),
                Forms\Components\Select::make('type')
                    ->label('النوع')
                    ->options([
                        'text' => 'نص',
                        'image' => 'صورة',
                        'color' => 'لون',
                        'gif' => 'صورة متحركة (GIF)',
                    ])
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('key')
                    ->label('المفتاح')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('value')
                    ->label('القيمة')
                    ->limit(50),
                Tables\Columns\TextColumn::make('type')
                    ->label('النوع')
                    ->badge(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('آخر تحديث')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
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
            ])
            ->bulkActions([
                // No bulk actions for settings
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
        $pages = [
            'index' => Pages\ListSettings::route('/'),
        ];
        
        // إضافة صفحة إعدادات النظام فقط للادمن
        $user = auth()->user();
        if ($user) {
            $userRoles = $user->getRoleNames()->toArray();
            if (in_array('Admin', $userRoles) && count($userRoles) === 1) {
                $pages['system-settings'] = Pages\SystemSettings::route('/system-settings');
            }
        }
        
        return $pages;
    }
}

