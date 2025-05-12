<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Hash;

use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pass = Hash::make("password");

        DB::table("users")->insert([
            [
                'name' => fake()->name,
                'email' => fake()->email,
                'password' => $pass,
            ]
        ]);
    }
}
