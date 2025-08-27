<?php

namespace ISS\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use ISS\App\Infrastructure\Models\RealEducationRoutesOfUser;
use ISS\App\Infrastructure\Models\EducationRoute;
use ISS\App\Infrastructure\Models\RealEducationRoutePoint;
use ISS\App\Infrastructure\Models\UserData;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class RealEducationRoutesOfUserFactory extends Factory
{
    protected $model = RealEducationRoutesOfUser::class; //задал свойство чтобы использовать модель с произвольным расположением

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_data_id' => UserData::factory(), //сочетание полей должно быть уникальным
            'route_id' => EducationRoute::factory(), //сочетание полей должно быть уникальным
            'last_pass_point_id' => RealEducationRoutePoint::factory(),
        ];
    }
}
