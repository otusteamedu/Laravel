<?php

namespace ISS\Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use ISS\Database\Seeders\EducationRouteSeeder;
use ISS\Database\Seeders\EducationRoutePointSeeder;
use ISS\Database\Seeders\EducationMaterialTypeSeeder;
use ISS\Database\Seeders\EducationMaterialSeeder;
use ISS\Database\Seeders\ExamQuestionSeeder;
use ISS\Database\Seeders\ExamAnswerSeeder;
use ISS\Database\Seeders\RealEducationRoutePointSeeder;
use ISS\Database\Seeders\RealEducationRoutesOfUserSeeder;
use ISS\Database\Seeders\UserRoleSeeder;
use ISS\Database\Seeders\UserDataSeeder;
use ISS\Database\Seeders\FillInitialDataSeeder;

class BaseISSSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //$this->call(EducationRouteSeeder::class);
        //$this->call(EducationRoutePointSeeder::class);
        //$this->call(EducationMaterialTypeSeeder::class);
        //$this->call(EducationMaterialSeeder::class);
        //$this->call(ExamQuestionTypeSeeder::class);
        //$this->call(ExamQuestionSeeder::class);
        //$this->call(ExamAnswerSeeder::class);
        //$this->call(RealEducationRoutePointSeeder::class);
        //$this->call(RealEducationRoutesOfUserSeeder::class);
        //$this->call(UserRoleSeeder::class);
        //$this->call(UserDataSeeder::class);

        $this->call(FillInitialDataSeeder::class);
    }
}
