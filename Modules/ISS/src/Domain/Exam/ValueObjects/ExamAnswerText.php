<?php

namespace ISS\App\Domain\Exam\ValueObjects;

use InvalidArgumentException;
use ISS\App\Domain\SharedValueObjects\Id;

/**
 * @var int $id код вопроса
 * @var string $answer текст вопроса
 */

class ExamAnswerText
{
    public int $id;
    public string $answer;

    public function __construct(int $id, string $answer)
    {
        $this->id = (new Id($id))->id;

        if (empty($answer)) {
            throw new InvalidArgumentException("Answer must be not empty!");
        }
        $this->answer = $answer;
    }
}
