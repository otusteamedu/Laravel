<?php

namespace Database\Factories;

use App\EloquentModels\Measure;
use App\EloquentModels\MeasureProductRecipe;
use App\EloquentModels\Product;
use App\EloquentModels\Recipe;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\EloquentModels\MeasureProductRecipe>
 */
class MeasureProductRecipeFactory extends Factory
{
    protected $model = MeasureProductRecipe::class;

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
