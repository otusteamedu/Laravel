<?php

namespace Database\Factories;

use App\Models\Area;
use App\Models\Category;
use App\Models\Recipe;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Recipe>
 */
class RecipeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $categoryId = Category::inRandomOrder()->first()->id ?? null;
        $areaId = Area::inRandomOrder()->first()->id ?? null;
        $alternate = Recipe::inRandomOrder()->first()->name_en ?? null;
        return [
            'api_id' => $this->faker->unique()->numberBetween(1, 10000),
            'name_en' => $this->faker->word(),
            'alternate' => $alternate,
            'category_id' => $categoryId,
            'instruction_en' => $this->faker->paragraph(),
            'area_id' => $areaId,
        ];
    }
}
