<?php

namespace ISS\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use ISS\App\Infrastructure\Models\ExamQuestion;
use ISS\App\Infrastructure\Models\ExamQuestionType;
use ISS\App\Infrastructure\Models\EducationRoutePoint;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ExamQuestionFactory extends Factory
{
    protected $model = ExamQuestion::class; //задал свойство чтобы использовать модель с произвольным расположением

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'short_question_name' => fake()->word(),
            'question' => fake()->unique()->sentence(),  //временное реш-е т.к. жолжно быть уникально сочетание полей
            'point_id' => EducationRoutePoint::factory(),
            'question_type_id' => ExamQuestionType::factory(),
        ];
    }
}
