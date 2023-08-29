<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->truncate();

        $users = [
            [
                'name' => 'john doe',
                'email' => 'john@email.com',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'jane doe',
                'email' => 'jane@gmail.com',
                'password' => Hash::make('password'),
            ],
        ];

        foreach ($users as $user){
            User::create($user);
        }
    }
}
