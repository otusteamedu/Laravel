<?php

namespace ISS\App\Domain\Exam\ValueObjects;

use InvalidArgumentException;
use ISS\App\Domain\SharedValueObjects\Id;

/***
 * @var Id $questionId код вопроса из экзаменационной формы
 * @var int|string $answerId код ответа из экзаменационной формы
 */

class QuestionAndAnswerFromExamForm
{
    public Id $questionId;
    public int|string|null $answerId;

    public function __construct(int $questionId, int|string|null $answerId)
    {
        $this->questionId = new Id($questionId);

        if (is_numeric($answerId)) {
            if ($answerId < 0) {
                throw new InvalidArgumentException("Answer id must be a positive integer or null");
            }
        }
        $this->answerId = ($answerId);
    }
}
