<?php

namespace ISS\Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use ISS\App\Infrastructure\Models\RealEducationRoutePoint;

class RealEducationRoutePointSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        RealEducationRoutePoint::factory(5)->create();
    }
}
