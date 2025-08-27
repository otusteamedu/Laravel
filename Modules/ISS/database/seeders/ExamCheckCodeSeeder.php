<?php

namespace ISS\Database\Seeders;


use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use ISS\App\Infrastructure\Models\ExamCheckCode;

class ExamCheckCodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ExamCheckCode::factory()->count(5)->create();
    }
}
