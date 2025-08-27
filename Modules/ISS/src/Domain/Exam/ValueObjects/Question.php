<?php

namespace ISS\App\Domain\Exam\ValueObjects;

use InvalidArgumentException;
use ISS\App\Domain\SharedValueObjects\Id;
use ISS\App\Domain\Exam\ValueObjects\Answer;

/**
 * @var Id $id код вопроса
 * @var string $questionName название вопроса
 * @var string $questionText текст вопроса
 * @var Id $refPointId код справочной точки маршрута, к которой относится экзаменационный вопрос
 * @var null|array<Answer> $answers ответы для этого экзаменационного вопроса
 */

class Question
{
    public Id $id;
    public string $questionName;
    public string $questionText;
    public Id $refPointId;
    public null|array $answers;

    public function __construct(int $id, string $questionName, string $questionText, int $refPointId, array|null $answers)
    {
        $this->id = new Id($id);

        if (empty($questionName)) {
            throw new InvalidArgumentException("Question name can't be empty");
        }
        $this->questionName = $questionName;

        if (empty($questionText)) {
            throw new InvalidArgumentException("Question text can't be empty");
        }
        $this->questionText = $questionText;

        $this->refPointId = new Id($refPointId);

        $this->answers = $answers;
    }
}
