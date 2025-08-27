<?php

namespace ISS\App\Domain\Exam\ValueObjects;

use InvalidArgumentException;
use ISS\App\Domain\SharedValueObjects\Id;

/**
 * @var int $id код вопроса
 * @var string $question текст вопроса
 */

class ExamQuestionText
{
    public int $id;
    public string $question;

    public function __construct(int $id, string $question)
    {
        $this->id = (new Id($id))->id;

        if (empty($question)) {
            throw new InvalidArgumentException("Question must be not empty!");
        }
        $this->question = $question;
    }
}
