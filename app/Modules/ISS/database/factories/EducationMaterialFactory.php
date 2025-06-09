<?php

namespace App\Modules\ISS\database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Modules\ISS\src\Models\EducationMaterial;
use App\Modules\ISS\src\Models\EducationMaterialType;
use App\Modules\ISS\src\Models\EducationRoutePoint;

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
