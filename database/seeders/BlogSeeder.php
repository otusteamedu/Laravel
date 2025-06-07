<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BlogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('blogs')->insert(
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
