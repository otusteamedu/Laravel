<?php

namespace App\Modules\ISS\database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Modules\ISS\src\Models\Teacher;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class TeacherFactory extends Factory
{
    protected $model = Teacher::class; //задал свойство чтобы использовать модель с произвольным расположением

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'connected_organization' => fake()->unique()->company(),
            'teacher_email' => fake()->unique()->email(),
        ];
    }
}
