<?php

namespace ISS\App\Domain\Exam\ValueObjects;

use InvalidArgumentException;
use ISS\App\Domain\SharedValueObjects\Id;

/**
 * @var Id $id код ответа
 * @var string $answerName название ответа
 * @var string $answerText текст ответа
 * @var Id $questionId код вопроса, к которому относится ответ
 * @var null|string $isRight отметка что ответ правильный
 */

class Answer
{
    public Id $id;
    public string $answerName;
    public string $answerText;
    public Id $questionId;
    public null|string $isRight;

    public function __construct(int $id, string $answerName, string $answerText, int $questionId, null|string $isRight)
    {
        $this->id = new Id($id);

        if (empty($answerName)) {
            throw new InvalidArgumentException('Answer name cannot be empty.');
        }
        $this->answerName = $answerName;

        if (empty($answerText)) {
            throw new InvalidArgumentException('Answer text cannot be empty.');
        }
        $this->answerText = $answerText;

        $this->questionId = new Id($questionId);

        if(empty($isRight)) {
            throw new InvalidArgumentException('Flag isWright cannot be empty.');
        }
        $this->isRight = $isRight;
    }
}
