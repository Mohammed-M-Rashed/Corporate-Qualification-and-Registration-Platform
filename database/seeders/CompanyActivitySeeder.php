<?php

namespace Database\Seeders;

use App\Models\CompanyActivity;
use Illuminate\Database\Seeder;

class CompanyActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $activities = [
            ['name' => 'أعمال هندسية واستشارية', 'is_active' => true],
            ['name' => 'مقاولات إنشاءات مدنية', 'is_active' => true],
            ['name' => 'مقاولات إنشاءات صناعية', 'is_active' => true],
            ['name' => 'أعمال النفط والغاز', 'is_active' => true],
            ['name' => 'صيانة معدات وآليات', 'is_active' => true],
            ['name' => 'نقل وخدمات لوجستية', 'is_active' => true],
            ['name' => 'توريد معدات ومواد', 'is_active' => true],
            ['name' => 'أعمال كهرباء وميكانيك', 'is_active' => true],
            ['name' => 'أعمال سلامة وصحة مهنية', 'is_active' => true],
            ['name' => 'خدمات بيئية ومعالجة', 'is_active' => true],
            ['name' => 'حفر وآبار', 'is_active' => true],
            ['name' => 'إنشاء خطوط أنابيب', 'is_active' => true],
            ['name' => 'تركيب وصيانة محطات', 'is_active' => true],
            ['name' => 'استشارات فنية وقانونية', 'is_active' => true],
            ['name' => 'تدريب وتأهيل كوادر', 'is_active' => true],
        ];

        foreach ($activities as $activity) {
            CompanyActivity::firstOrCreate(
                ['name' => $activity['name']],
                ['is_active' => $activity['is_active']]
            );
        }
    }
}
