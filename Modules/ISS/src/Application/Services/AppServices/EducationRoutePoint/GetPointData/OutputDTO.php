<?php

declare(strict_types=1);

namespace ISS\App\Application\Services\AppServices\EducationRoutePoint\GetPointData;

use ISS\App\Application\Services\Exam\EducationQuestion\GetRefPointExamQuestions\OutputDTO as QuestionDTO;
use ISS\App\Application\Services\EducationMaterial\GetAllMaterialsOfRefPoint\OutputDTO as MaterialDTO;

/**
 * @var array<QuestionDTO> $questions вопросы для экзаменационного теста, вместе с ответами
 * @var array<MaterialDTO> $materials инструкции для справочной точки маршрута
 */

class OutputDTO
{
    public function __construct(
        public array $questions,
        public array $materials,
    )
    {
    }
}
