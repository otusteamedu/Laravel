<?php

namespace ISS\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use ISS\App\Infrastructure\Models\EducationMaterialType;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class EducationMaterialTypeFactory extends Factory
{
    protected $model = EducationMaterialType::class; //задал свойство чтобы использовать модель с произвольным расположением

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->word(),
        ];
    }
}
