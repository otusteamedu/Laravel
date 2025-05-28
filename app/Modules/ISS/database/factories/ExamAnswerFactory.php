<?php

namespace App\Modules\ISS\database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Modules\ISS\src\Models\ExamAnswer;
use App\Modules\ISS\src\Models\ExamQuestion;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ExamAnswerFactory extends Factory
{
    protected $model = ExamAnswer::class; //задал свойство чтобы использовать модель с произвольным расположением

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'short_answer_name' => $this->faker->word(),
            'answer' => $this->faker->unique()->sentence(), //временное реш-е т.к. должно быть уникально сочетание полей
            'question_id' => ExamQuestion::factory(),
            'is_right' => fake()->randomElement(['Y', 'N']),
        ];
    }
}
