<?php

namespace Database\Factories;

use App\EloquentModels\Area;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\EloquentModels\Area>
 */
class AreaFactory extends Factory
{
    protected $model = Area::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name_ru' => $this->faker->country(),
        ];
    }
}
