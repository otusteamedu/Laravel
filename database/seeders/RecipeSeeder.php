<?php

namespace Database\Seeders;

use App\Infrastructure\EloquentModels\Recipe;
use Illuminate\Database\Seeder;

class RecipeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Recipe::factory()->count(10)->create();
        
    }
}
