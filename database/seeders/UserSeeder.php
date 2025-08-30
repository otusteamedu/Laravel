<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert(
            [
                [
                    'name' => 'olga',
                    'email' => 'olga@mail.ru',
                    'password' => Hash::make('123456'),
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'admin',
                    'email' => 'admin@mail.ru',
                    'password' => Hash::make('123456'),
                    'created_at' => now(),
                    'updated_at' => now(),
                ],

            ]
        );
    }
}
