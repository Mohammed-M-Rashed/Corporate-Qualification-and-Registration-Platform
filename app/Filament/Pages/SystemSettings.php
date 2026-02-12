<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SystemSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected string $view = 'filament.pages.system-settings';

    protected static ?string $navigationLabel = 'إعدادات النظام';

    protected static ?string $title = 'إعدادات النظام';

    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-o-cog-6-tooth';
    }

    public static function shouldRegisterNavigation(): bool
    {
        $user = auth()->user();
        if (!$user) {
            return false;
        }
        
        // فقط Admin يرون إعدادات النظام
        return $user->hasRole('Admin');
    }

    public static function canAccess(): bool
    {
        $user = auth()->user();
        if (!$user) {
            return false;
        }
        
        // فقط Admin يمكنهم الوصول إلى هذه الصفحة
        return $user->hasRole('Admin');
    }

    public ?array $data = [];

    public function mount(): void
    {
        // التحقق من الصلاحيات عند الوصول للصفحة
        if (!static::canAccess()) {
            abort(403, 'غير مصرح لك بالوصول إلى هذه الصفحة');
        }
        
        $this->form->fill([
            'system_logo' => Setting::where('key', 'system_logo')->value('value'),
            'primary_color' => Setting::where('key', 'primary_color')->value('value') ?? '#007bff',
            'loading_gif' => Setting::where('key', 'loading_gif')->value('value'),
            'smtp_host' => Setting::where('key', 'smtp_host')->value('value'),
            'smtp_port' => Setting::where('key', 'smtp_port')->value('value'),
            'smtp_username' => Setting::where('key', 'smtp_username')->value('value'),
            'smtp_password' => Setting::where('key', 'smtp_password')->value('value'),
            'smtp_encryption' => Setting::where('key', 'smtp_encryption')->value('value') ?? 'tls',
            'registration_enabled' => Setting::where('key', 'registration_enabled')->value('value') ?? '1',
            'qualification_validity_days' => Setting::where('key', 'qualification_validity_days')->value('value') ?? '365',
        ]);
    }

    protected function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('المظهر')
                    ->schema([
                        Forms\Components\FileUpload::make('system_logo')
                            ->label('شعار النظام')
                            ->image()
                            ->directory('settings')
                            ->columnSpanFull(),
                        Forms\Components\ColorPicker::make('primary_color')
                            ->label('اللون الأساسي')
                            ->default('#007bff'),
                        Forms\Components\FileUpload::make('loading_gif')
                            ->label('صورة التحميل (GIF)')
                            ->acceptedFileTypes(['image/gif'])
                            ->directory('settings')
                            ->columnSpanFull(),
                    ]),
                Section::make('إعدادات البريد الإلكتروني')
                    ->schema([
                        Forms\Components\TextInput::make('smtp_host')
                            ->label('خادم SMTP')
                            ->required(),
                        Forms\Components\TextInput::make('smtp_port')
                            ->label('منفذ SMTP')
                            ->numeric()
                            ->default(587)
                            ->required(),
                        Forms\Components\TextInput::make('smtp_username')
                            ->label('اسم مستخدم SMTP')
                            ->required(),
                        Forms\Components\TextInput::make('smtp_password')
                            ->label('كلمة مرور SMTP')
                            ->password()
                            ->required(),
                        Forms\Components\Select::make('smtp_encryption')
                            ->label('تشفير SMTP')
                            ->options([
                                'tls' => 'TLS',
                                'ssl' => 'SSL',
                            ])
                            ->default('tls')
                            ->required(),
                    ]),
                Section::make('إعدادات النظام')
                    ->schema([
                        Forms\Components\Toggle::make('registration_enabled')
                            ->label('تفعيل التسجيل')
                            ->default(true),
                        Forms\Components\TextInput::make('qualification_validity_days')
                            ->label('مدة صلاحية التأهيل (بالأيام)')
                            ->numeric()
                            ->default(365)
                            ->required(),
                    ]),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        foreach ($data as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value, 'type' => $this->getSettingType($key)]
            );
        }

        \Filament\Notifications\Notification::make()
            ->title('تم حفظ الإعدادات بنجاح')
            ->success()
            ->send();
    }

    protected function getSettingType(string $key): string
    {
        return match ($key) {
            'system_logo', 'loading_gif' => 'image',
            'primary_color' => 'color',
            'registration_enabled' => 'boolean',
            'qualification_validity_days' => 'number',
            default => 'text',
        };
    }
}

