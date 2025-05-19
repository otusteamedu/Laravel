<?php

namespace App\Modules\ISS\database\seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Modules\ISS\database\seeders\EducationRouteSeeder;
use App\Modules\ISS\database\seeders\EducationRoutePointSeeder;
use App\Modules\ISS\database\seeders\EducationMaterialTypeSeeder;
use App\Modules\ISS\database\seeders\EducationMaterialSeeder;
use App\Modules\ISS\database\seeders\ExamQuestionSeeder;
use App\Modules\ISS\database\seeders\ExamAnswerSeeder;
use App\Modules\ISS\database\seeders\RealEducationRoutePointSeeder;
use App\Modules\ISS\database\seeders\RealEducationRoutesOfUserSeeder;
use App\Modules\ISS\database\seeders\UserRoleSeeder;
use App\Modules\ISS\database\seeders\UserDataSeeder;


class BaseISSSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call(EducationRouteSeeder::class);
        $this->call(EducationRoutePointSeeder::class);
        $this->call(EducationMaterialTypeSeeder::class);
        $this->call(EducationMaterialSeeder::class);
        $this->call(ExamQuestionSeeder::class);
        $this->call(ExamAnswerSeeder::class);
        $this->call(RealEducationRoutePointSeeder::class);
        $this->call(RealEducationRoutesOfUserSeeder::class);
        $this->call(UserRoleSeeder::class);
        $this->call(UserDataSeeder::class);
    }
}
