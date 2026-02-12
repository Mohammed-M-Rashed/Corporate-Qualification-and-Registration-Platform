<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cities = [
            ['name' => 'طرابلس', 'is_active' => true],
            ['name' => 'بنغازي', 'is_active' => true],
            ['name' => 'مصراتة', 'is_active' => true],
            ['name' => 'سبها', 'is_active' => true],
            ['name' => 'زليتن', 'is_active' => true],
            ['name' => 'البيضاء', 'is_active' => true],
            ['name' => 'زوارة', 'is_active' => true],
            ['name' => 'أجدابيا', 'is_active' => true],
            ['name' => 'درنة', 'is_active' => true],
            ['name' => 'غريان', 'is_active' => true],
            ['name' => 'يفرن', 'is_active' => true],
            ['name' => 'صبراتة', 'is_active' => true],
            ['name' => 'طبرق', 'is_active' => true],
            ['name' => 'الخمس', 'is_active' => true],
            ['name' => 'سرت', 'is_active' => true],
        ];

        foreach ($cities as $city) {
            City::firstOrCreate(
                ['name' => $city['name']],
                $city
            );
        }
    }
}
