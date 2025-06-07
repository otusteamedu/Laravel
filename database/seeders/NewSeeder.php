<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('news')->insert(
            [
                [
                    'title' => fake()->text(50),
                    'preview' => fake()->text(100),
                    'text' => fake()->text(500),
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'title' => fake()->text(50),
                    'preview' => fake()->text(100),
                    'text' => fake()->text(500),
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'title' => fake()->text(50),
                    'preview' => fake()->text(100),
                    'text' => fake()->text(500),
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'title' => fake()->text(50),
                    'preview' => fake()->text(100),
                    'text' => fake()->text(500),
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'title' => fake()->text(50),
                    'preview' => fake()->text(100),
                    'text' => fake()->text(500),
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]
        );
    }
}
