<?php

namespace Database\Seeders;

use App\Models\CommitteeType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CommitteeTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            [
                'name' => 'Qualification',
                'name_ar' => 'لجنة التأهيل',
                'description' => 'لجنة تقييم وتأهيل الشركات والمقاولين',
                'is_active' => true,
            ],
            [
                'name' => 'Bidding',
                'name_ar' => 'لجنة العطاءات',
                'description' => 'لجنة إدارة وتقييم العطاءات',
                'is_active' => true,
            ],
            [
                'name' => 'Procurement',
                'name_ar' => 'لجنة المشتريات',
                'description' => 'لجنة إدارة المشتريات',
                'is_active' => true,
            ],
        ];

        foreach ($types as $type) {
            CommitteeType::firstOrCreate(
                ['name' => $type['name']],
                $type
            );
        }
    }
}
