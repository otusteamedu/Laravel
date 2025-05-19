<?php

namespace App\Modules\ISS\database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Modules\ISS\src\Models\UserData;
use App\Modules\ISS\src\Models\UserRole;
//use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class UserDataFactory extends Factory
{
    protected $model = UserData::class; //задал свойство чтобы использовать модель с произвольным расположением

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //'user_id' => User::factory(),
            'role_id' => UserRole::factory(),
            'user_iss_login' => fake()->unique()->word(), //должно быть уникальное сочетание полей (пока временное решение)
            'user_iss_password' => fake()->password(5, 20), //должно быть уникальное сочетание полей (пока временное решение)
        ];
    }
}
