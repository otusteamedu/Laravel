<?php

namespace Database\Seeders;

use App\EloquentModels\Measure;
use App\EloquentModels\Product;
use App\EloquentModels\Recipe;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
