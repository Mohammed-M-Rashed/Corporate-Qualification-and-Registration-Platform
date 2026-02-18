<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // بيانات تشغيل النظام فقط (أدوار، صلاحيات، مستخدم افتراضي، إعدادات، أنواع لجان، مدن)
        $this->call([
            RolePermissionSeeder::class,
            CommitteeTypeSeeder::class,
            UserSeeder::class,
            CommitteeSeeder::class,
            SettingSeeder::class,
            CitySeeder::class,
            CompanyActivitySeeder::class,
            RejectionReasonSeeder::class,
            FaqSeeder::class
        ]);
    }
}
