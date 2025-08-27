<?php

declare(strict_types=1);

namespace ISS\App\Application\Services\Exam\EducationQuestion\GetAnswersText;

use ISS\App\Application\Services\Exam\EducationQuestion\GetAnswersText\AnswerTextDTO;

/**
 * @var array<AnswerTextDTO> $answerTexts массив текстов для экзаменационных ответов
 */

class OutputDTO
{
    public function __construct(
        public array $answerTexts,
    )
    {
    }
}
