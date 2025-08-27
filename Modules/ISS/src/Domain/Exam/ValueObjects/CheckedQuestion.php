<?php

namespace ISS\App\Domain\Exam\ValueObjects;

use InvalidArgumentException;
use ISS\App\Domain\SharedValueObjects\Id;

/**
 * @var Id $questionId код вопроса
 * @var int|string $answerId ответ на вопрос (из формы сдачи экзамена с фронта, для простого код, для сложного текст)
 * @var int|null $rightAnswerId код правильного ответа на вопрос (из БД, может быть 0-... или null)
 */

class CheckedQuestion
{
    public int $questionId;
    public int|string|null $answerId;
    public int|null $rightAnswerId;

    public function __construct(int $questionId, int|string|null $answerId, int|null $rightAnswerId)
    {
        $this->questionId = (new Id($questionId))->id; //вопрос как использовать общее правило проверки не через valueObj

        if (is_numeric($answerId)) {
            if ($answerId < 0) { //оставляю возможность ставить 0 т.к. если не указан ответ то он ставит 0
                throw new InvalidArgumentException("answerId must be a positive integer");
            }
        }
        $this->answerId = $answerId;

        if (!is_null($rightAnswerId)) {
            if ($rightAnswerId < 0) {
                throw new InvalidArgumentException("Id of Right Answer must be positive integer");
            }
        }
        $this->rightAnswerId = $rightAnswerId;
    }

}
