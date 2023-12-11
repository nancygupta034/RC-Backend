<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AgeRangeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $age_ranges = [
            [
                'name' => 'Child',
                'icon' => 'icon-kid',
                'min_age' => 0,
                'max_age' => 12,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Adolescent',
                'icon' => 'icon-teenage',
                'min_age' => 12,
                'max_age' => 18,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Adult',
                'icon' => 'icon-adult',
                'min_age' => 18,
                'max_age' => 65,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Geriatric',
                'icon' => 'icon-old',
                'min_age' => 65,
                'max_age' => 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]
        ];

        DB::table('age_ranges')->insert($age_ranges);
    }
}
