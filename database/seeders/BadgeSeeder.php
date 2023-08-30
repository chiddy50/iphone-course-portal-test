<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Badge;
use Illuminate\Support\Facades\DB;

class BadgeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('badges')->truncate();

        $badges = [
            [
                'name' => 'beginner',
                'required_achievements' => 0,
                'level' => 1
            ],
            [
                'name' => 'intermediate',
                'required_achievements' => 4,
                'level' => 2
            ],
            [
                'name' => 'advanced',
                'required_achievements' => 8,
                'level' => 3
            ],
            [
                'name' => 'master',
                'required_achievements' => 10,
                'level' => 4
            ],
        ];

        foreach ($badges as $badge){
            Badge::create($badge);
        }
    }
}
