<?php

namespace ISS\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use ISS\App\Infrastructure\Models\EducationMaterial;
use ISS\App\Infrastructure\Models\EducationMaterialType;
use ISS\App\Infrastructure\Models\EducationRoutePoint;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class EducationMaterialFactory extends Factory
{
    protected $model = EducationMaterial::class; //задал свойство чтобы использовать модель с произвольным расположением

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->unique()->word(),
            'material_type_id' => EducationMaterialType::factory(),
            'point_id' => EducationRoutePoint::factory(),
            'file_path' => fake()->unique()->url(),
        ];
    }
}
