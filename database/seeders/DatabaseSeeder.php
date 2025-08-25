<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\QuestionChartTypeSeeder;
use Database\Seeders\QuestionTypeSeeder;
use Database\Seeders\PollSeeder;
use Database\Seeders\PollAnswerSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(
            [
                QuestionChartTypeSeeder::class,
                QuestionTypeSeeder::class,
                PollSeeder::class,
                PollAnswerSeeder::class
            ]
        );
    }
}
