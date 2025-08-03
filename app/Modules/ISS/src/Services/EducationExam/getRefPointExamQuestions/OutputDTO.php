<?php

declare(strict_types=1);

namespace App\Modules\ISS\src\Services\EducationExam\getRefPointExamQuestions;

use App\Modules\ISS\src\Services\EducationExam\getRefPointExamQuestions\QuestionDTO;

/**
 * @var array<QuestionDTO> $questions экзаменационные вопросы
 */

class OutputDTO
{
    public function __construct(
        public array $questions,
    )
    {
    }
}
