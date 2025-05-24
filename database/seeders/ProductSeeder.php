<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Recipe;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::factory()->count(20)->create();
        for ($i = 0; $i < 30; $i++) {
            $product = Product::inRandomOrder()->first()->id;
            $recipe = Recipe::inRandomOrder()->first();
            $recipe->products()->attach($product);
        }
    }
}
