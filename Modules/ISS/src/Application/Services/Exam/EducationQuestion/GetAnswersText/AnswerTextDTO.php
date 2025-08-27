<?php
declare(strict_types=1);

namespace ISS\App\Application\Services\Exam\EducationQuestion\GetAnswersText;

/**
 * @var int $id код ответа
 * @var string $answer текст ответа
 */

class AnswerTextDTO
{
    public function __construct(
        public int $id,
        public string $answer,
    )
    {
    }
}
