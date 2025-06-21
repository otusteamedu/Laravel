<?php

namespace App\Modules\ISS\database\seeders;


use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Modules\ISS\src\Models\ExamCheckCode;

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
