<?php
namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserProfileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'phone' => $this->faker->phoneNumber,
            'address' => $this->faker->address,
            'birth_date' => $this->faker->date(),
            'about' => $this->faker->paragraph,
        ];
    }

    /**
     * Указываем, что профиль без дополнительной информации
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function minimal()
    {
        return $this->state(function (array $attributes) {
            return [
                'phone' => null,
                'address' => null,
                'birth_date' => null,
                'about' => null,
            ];
        });
    }

    /**
     * Указываем конкретного пользователя
     *
     * @param  int  $userId
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function forUser(int $userId)
    {
        return $this->state(function (array $attributes) use ($userId) {
            return [
                'user_id' => $userId,
            ];
        });
    }
}
