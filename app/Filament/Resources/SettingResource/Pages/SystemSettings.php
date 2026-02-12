<?php

namespace App\Filament\Resources\SettingResource\Pages;

use App\Filament\Resources\SettingResource;
use App\Models\Setting;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Resources\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SystemSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $resource = SettingResource::class;

    protected string $view = 'filament.resources.setting-resource.pages.system-settings';

    protected static ?string $title = 'إعدادات النظام';

    protected static ?string $navigationLabel = 'إعدادات النظام';

    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-o-cog-6-tooth';
    }

    public static function shouldRegisterNavigation(array $parameters = []): bool
    {
        $user = auth()->user();
        if (!$user) {
            return false;
        }
        
        // فقط Admin يرون إعدادات النظام
        // التحقق من أن المستخدم لديه دور Admin فقط وليس لديه أدوار أخرى
        $userRoles = $user->getRoleNames()->toArray();
        return in_array('Admin', $userRoles) && count($userRoles) === 1;
    }

    public static function canAccess(array $parameters = []): bool
    {
        $user = auth()->user();
        if (!$user) {
            return false;
        }
        
        // فقط Admin يمكنهم الوصول إلى هذه الصفحة
        $userRoles = $user->getRoleNames()->toArray();
        return in_array('Admin', $userRoles) && count($userRoles) === 1;
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
            'primary_color' => Setting::where('key', 'primary_color')->value('value') ?? '#1e40af',
            'loading_gif' => Setting::where('key', 'loading_gif')->value('value'),
            'smtp_host' => Setting::where('key', 'smtp_host')->value('value'),
            'smtp_port' => Setting::where('key', 'smtp_port')->value('value'),
            'smtp_username' => Setting::where('key', 'smtp_username')->value('value'),
            'smtp_password' => Setting::where('key', 'smtp_password')->value('value'),
            'smtp_encryption' => Setting::where('key', 'smtp_encryption')->value('value') ?? 'tls',
            'smtp_from_address' => Setting::where('key', 'smtp_from_address')->value('value'),
            'smtp_from_name' => Setting::where('key', 'smtp_from_name')->value('value'),
            'registration_enabled' => Setting::where('key', 'registration_enabled')->value('value') ?? '1',
            'qualification_duration' => Setting::where('key', 'qualification_duration')->value('value') ?? '12',
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
                            ->default('#1e40af'),
                        Forms\Components\FileUpload::make('loading_gif')
                            ->label('صورة التحميل (صورة متحركة)')
                            ->acceptedFileTypes(['image/gif'])
                            ->directory('settings')
                            ->columnSpanFull(),
                    ]),
                Section::make('إعدادات البريد الإلكتروني')
                    ->schema([
                        Forms\Components\TextInput::make('smtp_host')
                            ->label('خادم SMTP'),
                        Forms\Components\TextInput::make('smtp_port')
                            ->label('منفذ SMTP')
                            ->numeric()
                            ->default(587),
                        Forms\Components\TextInput::make('smtp_username')
                            ->label('اسم مستخدم SMTP'),
                        Forms\Components\TextInput::make('smtp_password')
                            ->label('كلمة مرور SMTP')
                            ->password(),
                        Forms\Components\Select::make('smtp_encryption')
                            ->label('تشفير SMTP')
                            ->options([
                                'tls' => 'TLS (نقل آمن)',
                                'ssl' => 'SSL (طبقة آمنة)',
                            ])
                            ->default('tls'),
                        Forms\Components\TextInput::make('smtp_from_address')
                            ->label('عنوان المرسل')
                            ->email(),
                        Forms\Components\TextInput::make('smtp_from_name')
                            ->label('اسم المرسل'),
                    ]),
                Section::make('إعدادات النظام')
                    ->schema([
                        Forms\Components\Toggle::make('registration_enabled')
                            ->label('تفعيل التسجيل')
                            ->default(true),
                        Forms\Components\TextInput::make('qualification_duration')
                            ->label('مدة صلاحية التأهيل (بالأشهر)')
                            ->numeric()
                            ->default(12)
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
            'qualification_duration' => 'number',
            default => 'text',
        };
    }
}

