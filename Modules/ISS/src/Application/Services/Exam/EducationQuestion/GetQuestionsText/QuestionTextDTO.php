<?php
declare(strict_types=1);

namespace ISS\App\Application\Services\Exam\EducationQuestion\GetQuestionsText;

/**
 * @var int $id код вопроса
 * @var string $question текст вопроса
 */

class QuestionTextDTO
{
    public function __construct(
        public int $id,
        public string $question,
    )
    {
    }
}
