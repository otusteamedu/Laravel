<?php

declare(strict_types=1);

namespace ISS\App\Application\Services\Exam\EducationQuestion\GetQuestionsWithAnswers;

/**
 * @var array<QuestionDTO> $questionsWithAnswers массив вопросов с ответами
 */

class OutputDTO
{
    public function __construct(
        public array $questionsWithAnswers,
    )
    {
    }
}
