<?php

namespace ISS\Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use ISS\App\Infrastructure\Models\EducationRoutePoint;

class EducationRoutePointSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        EducationRoutePoint::factory(5)->create();
    }
}
