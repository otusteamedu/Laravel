<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for($i=0;$i<20;$i++){
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
}
