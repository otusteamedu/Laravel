<?php

namespace Database\Factories;

use App\Infrastructure\EloquentModels\Measure;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\EloquentModels\Model>
 */
class MeasureFactory extends Factory
{
    protected $model = Measure::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name_en' => $this->faker->word(),
        ];
    }
}
