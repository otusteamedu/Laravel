<?php

namespace App\Modules\ISS\database\seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Modules\ISS\src\Models\RealEducationRoutesOfUser;

class RealEducationRoutesOfUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        RealEducationRoutesOfUser::factory(5)->create();
    }
}
