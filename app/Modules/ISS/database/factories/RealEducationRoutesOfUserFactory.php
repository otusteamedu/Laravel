<?php

namespace App\Modules\ISS\database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Modules\ISS\src\Models\RealEducationRoutesOfUser;
use App\Modules\ISS\src\Models\EducationRoute;
use App\Modules\ISS\src\Models\RealEducationRoutePoint;
use App\Modules\ISS\src\Models\UserData;

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
