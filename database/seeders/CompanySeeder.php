<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cities = \App\Models\City::all();
        $tripoli = $cities->where('name', 'طرابلس')->first();
        $benghazi = $cities->where('name', 'بنغازي')->first();
        $misrata = $cities->where('name', 'مصراتة')->first();
        $sabha = $cities->where('name', 'سبها')->first();
        $zlitin = $cities->where('name', 'زليتن')->first();

        $companies = [
            [
                'name' => 'شركة البناء والتطوير',
                'city_id' => $tripoli?->id,
                'commercial_register_number' => 'CR-001',
                'commercial_register_start_date' => Carbon::now()->subYears(5),
                'commercial_register_end_date' => Carbon::now()->addYear(),
                'email' => 'company1@example.com',
                'phone' => '0912345678',
                'address' => 'طرابلس، ليبيا',
                'is_agent' => false,
                'is_qualified' => true,
                'qualification_expiry_date' => Carbon::now()->addMonths(6),
                'is_active' => true,
            ],
            [
                'name' => 'شركة المقاولات العامة',
                'city_id' => $benghazi?->id,
                'commercial_register_number' => 'CR-002',
                'commercial_register_start_date' => Carbon::now()->subYears(3),
                'commercial_register_end_date' => Carbon::now()->addMonths(6),
                'email' => 'company2@example.com',
                'phone' => '0923456789',
                'address' => 'بنغازي، ليبيا',
                'is_agent' => false,
                'is_qualified' => false,
                'is_active' => true,
            ],
            [
                'name' => 'شركة الإنشاءات الحديثة',
                'city_id' => $misrata?->id,
                'commercial_register_number' => 'CR-003',
                'commercial_register_start_date' => Carbon::now()->subYear(),
                'commercial_register_end_date' => Carbon::now()->addYear(),
                'email' => 'company3@example.com',
                'phone' => '0934567890',
                'address' => 'مصراتة، ليبيا',
                'is_agent' => false,
                'is_qualified' => false,
                'is_active' => true,
            ],
            [
                'name' => 'شركة المشاريع الكبرى',
                'city_id' => $sabha?->id,
                'commercial_register_number' => 'CR-004',
                'commercial_register_start_date' => Carbon::now()->subYears(10),
                'commercial_register_end_date' => Carbon::now()->addYears(2),
                'email' => 'company4@example.com',
                'phone' => '0945678901',
                'address' => 'سبها، ليبيا',
                'is_agent' => false,
                'is_qualified' => true,
                'qualification_expiry_date' => Carbon::now()->addYear(),
                'is_active' => true,
            ],
            [
                'name' => 'شركة التطوير العقاري',
                'city_id' => $zlitin?->id,
                'commercial_register_number' => 'CR-005',
                'commercial_register_start_date' => Carbon::now()->subMonths(6),
                'commercial_register_end_date' => Carbon::now()->addMonths(18),
                'email' => 'company5@example.com',
                'phone' => '0956789012',
                'address' => 'زليتن، ليبيا',
                'is_agent' => false,
                'is_qualified' => false,
                'is_active' => true,
            ],
        ];

        foreach ($companies as $companyData) {
            Company::firstOrCreate(
                ['email' => $companyData['email']],
                $companyData
            );
        }
    }
}

