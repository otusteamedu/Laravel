<?php

declare(strict_types=1);

namespace App\Modules\ISS\src\Services\EducationExam\getExamQuestions;

/**
 * @var int    $id     код ответа на вопрос для контрольного теста
 * @var string $answer текст ответа на вопрос для контрольного теста
 */

class AnswerDTO
{
    public function __construct(
        public int    $id,
        public string $answer
    )
    {
    }
}
