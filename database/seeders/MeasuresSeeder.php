<?php

namespace Database\Seeders;

use App\Infrastructure\EloquentModels\Measure;
use Illuminate\Database\Seeder;

class MeasuresSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Measure::factory()->count(10)->create();
    }
}
