<?php

declare(strict_types=1);

namespace ISS\App\Application\Services\Exam\EducationQuestion\GetRefPointExamQuestions;

/**
 * @var int $id код ответа
 * @var string $answerName название ответа
 * @var string $answerText текст ответа
 * @var int $questionId код вопроса, к которому относится ответ
 * @var null|string $isRight отметка что ответ правильный
 */

class AnswerDTO
{
    public function __construct(
        public int $id,
        public string $answerName,
        public string $answerText,
        public int $questionId,
        public null|string $isRight
    )
    {
    }
}
