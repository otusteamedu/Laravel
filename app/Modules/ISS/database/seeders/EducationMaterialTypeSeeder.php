<?php

namespace App\Modules\ISS\database\seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Modules\ISS\src\Models\EducationMaterialType;

class EducationMaterialTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        EducationMaterialType::factory()->count(5)->create();
    }
}
