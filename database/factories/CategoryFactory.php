<?php

namespace Database\Factories;

use App\EloquentModels\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\EloquentModels\Category>
 */
class CategoryFactory extends Factory
{
    protected $model = Category::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name_ru' => $this->faker->word(),
            'description_en' => $this->faker->text(),
            
        ];
    }
}
