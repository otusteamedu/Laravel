<?php

namespace Database\Seeders;

use App\Models\MeasureProductRecipe;
use Illuminate\Database\Seeder;

class MeasureProductRecipeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        MeasureProductRecipe::factory()->count(50)->create();
    }
}
