<?php

namespace Database\Factories;

use App\Models\Measure;
use App\Models\Product;
use App\Models\Recipe;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MeasureProductRecipe>
 */
class MeasureProductRecipeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $recipe = Recipe::inRandomOrder()->first();
        $product = Product::inRandomOrder()->first();
        $measure = Measure::inRandomOrder()->first();
        return [
            'recipe_id' => $recipe->id,
            'product_id' => $product->id,
            'measure_id' => $measure->id,
            'value' => $this->faker->randomFloat(2, 1, 100),
        ];
    }
}
