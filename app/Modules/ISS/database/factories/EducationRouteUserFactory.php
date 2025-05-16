<?php

namespace App\Modules\ISS\database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Modules\ISS\src\Models\EducationRouteUser;
use App\Modules\ISS\src\Models\EducationRoute;
use App\Modules\ISS\src\Models\EducationRouteEducationRoutePoint;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class EducationRouteUserFactory extends Factory
{
    protected $model = EducationRouteUser::class; //задал свойство чтобы использовать модель с произвольным расположением

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(), //сочетание полей должно быть уникальным
            'route_id' => EducationRoute::factory(), //сочетание полей должно быть уникальным
            'last_pass_point_id' => EducationRouteEducationRoutePoint::factory(),
        ];
    }
}
