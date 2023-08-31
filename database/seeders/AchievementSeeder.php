<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Achievement;

class AchievementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Achievement::truncate();

        $achievements = [
            [
                'name' => 'first lesson watched',
                'group' => 'lesson',
                'required_count' => 1,
            ],
            [
                'name' => '5 lessons watched',
                'group' => 'lesson',
                'required_count' => 5,
            ],
            [
                'name' => '10 lessons watched',
                'group' => 'lesson',
                'required_count' => 10,
            ],
            [
                'name' => '25 lessons watched',
                'group' => 'lesson',
                'required_count' => 25,
            ],
            [
                'name' => '50 lessons watched',
                'group' => 'lesson',
                'required_count' => 50,
            ],
            [
                'name' => 'first comment written',
                'group' => 'comment',
                'required_count' => 1,
            ],
            [
                'name' => '3 comments written',
                'group' => 'comment',
                'required_count' => 3,
            ],
            [
                'name' => '5 comments written',
                'group' => 'comment',
                'required_count' => 5,
            ],
            [
                'name' => '10 comments written',
                'group' => 'comment',
                'required_count' => 10,
            ],
            [
                'name' => '20 comments written',
                'group' => 'comment',
                'required_count' => 20,
            ]
        ];

        Achievement::insert($achievements);

    }
}
