<?php

namespace App\Modules\ISS\database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Modules\ISS\src\Models\RealEducationRoutePoint;
use App\Modules\ISS\src\Models\EducationRoute;
use App\Modules\ISS\src\Models\EducationRoutePoint;

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
        ];
    }
}
