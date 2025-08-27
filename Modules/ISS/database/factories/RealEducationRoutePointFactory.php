<?php

namespace ISS\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use ISS\App\Infrastructure\Models\RealEducationRoutePoint;
use ISS\App\Infrastructure\Models\EducationRoute;
use ISS\App\Infrastructure\Models\EducationRoutePoint;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class RealEducationRoutePointFactory extends Factory
{
    protected $model = RealEducationRoutePoint::class; //задал свойство чтобы использовать модель с произвольным расположением

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'route_point_id' => EducationRoutePoint::factory(), //должно быть уникально сочетание полей
            'route_id' => EducationRoute::factory(), //должно быть уникально сочетание полей
            'exam_date' => fake()->datetime(),
            'position' => fake()->unique()->numberBetween(1, 10000), //должно быть уникально сочетание полей
        ];
    }
}
