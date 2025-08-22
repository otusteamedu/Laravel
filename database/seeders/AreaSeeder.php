<?php

namespace Database\Seeders;

use App\Infrastructure\EloquentModels\Area;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AreaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Area::factory()->count(10)->create();
    }
}
