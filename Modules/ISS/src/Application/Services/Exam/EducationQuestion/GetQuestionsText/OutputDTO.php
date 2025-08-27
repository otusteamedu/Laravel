<?php

declare(strict_types=1);

namespace ISS\App\Application\Services\Exam\EducationQuestion\GetQuestionsText;

use ISS\App\Application\Services\Exam\EducationQuestion\GetQuestionsText\QuestionTextDTO;

/**
 * @var array<QuestionTextDTO> $questionTexts массив текстов для экзаменационных вопросов
 */

class OutputDTO
{
    public function __construct(
        public array $questionTexts,
    )
    {
    }
}
