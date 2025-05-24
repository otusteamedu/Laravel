<?php

namespace Database\Seeders;

use App\Models\Measure;
use App\Models\Product;
use App\Models\Recipe;
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
