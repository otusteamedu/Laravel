<?php

namespace App\Modules\ISS\database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Modules\ISS\src\Models\ExamCheckCode;
use App\Modules\ISS\src\Models\UserData;
use App\Modules\ISS\src\Models\RealEducationRoutePoint;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ExamCheckCodeFactory extends Factory
{
    protected $model = ExamCheckCode::class; //задал свойство чтобы использовать модель с произвольным расположением

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'exam_check_code' => fake()->unique()->randomNumber(9, true),
            'iss_user_id' => UserData::factory(),
            'real_route_point_id' => RealEducationRoutePoint::factory(),
        ];
    }
}
