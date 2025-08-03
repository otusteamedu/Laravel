<?php

declare(strict_types=1);

namespace App\Modules\ISS\src\Services\EducationExam\getRefPointExamQuestions;

use App\Modules\ISS\src\Services\EducationExam\getRefPointExamQuestions\AnswerDTO;

/**
 * @var int $id код вопроса
 * @var string $questionName название вопроса
 * @var string $questionText текст вопроса
 * @var null|array<AnswerDTO> $answers ответы для этого экзаменационного вопроса
 */

class QuestionDTO
{
    public function __construct(
        public int $id,
        public string $questionName,
        public string $questionText,
        public array $answers,

    )
    {
    }
}
