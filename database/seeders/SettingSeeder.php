<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            [
                'key' => 'system_logo',
                'value' => 'settings/logo.png',
                'type' => 'image',
            ],
            [
                'key' => 'primary_color',
                'value' => '#1e40af',
                'type' => 'color',
            ],
            [
                'key' => 'loading_gif',
                'value' => 'settings/loading.gif',
                'type' => 'gif',
            ],
            [
                'key' => 'registration_enabled',
                'value' => '1',
                'type' => 'text',
            ],
            [
                'key' => 'qualification_duration',
                'value' => '12',
                'type' => 'text',
            ],
            [
                'key' => 'smtp_host',
                'value' => 'smtp.mailtrap.io',
                'type' => 'text',
            ],
            [
                'key' => 'smtp_port',
                'value' => '2525',
                'type' => 'text',
            ],
            [
                'key' => 'smtp_username',
                'value' => '',
                'type' => 'text',
            ],
            [
                'key' => 'smtp_password',
                'value' => '',
                'type' => 'text',
            ],
            [
                'key' => 'smtp_encryption',
                'value' => 'tls',
                'type' => 'text',
            ],
            [
                'key' => 'smtp_from_address',
                'value' => 'noreply@noc.ly',
                'type' => 'text',
            ],
            [
                'key' => 'smtp_from_name',
                'value' => 'المؤسسة الوطنية للنفط',
                'type' => 'text',
            ],
        ];

        foreach ($settings as $setting) {
            Setting::firstOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}

