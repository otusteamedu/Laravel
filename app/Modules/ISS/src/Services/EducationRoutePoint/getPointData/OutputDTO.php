<?php

declare(strict_types=1);

namespace App\Modules\ISS\src\Services\EducationRoutePoint\getPointData;
use App\Modules\ISS\src\Services\EducationExam\getRefPointExamQuestions\OutputDTO as QuestionDTO;
use App\Modules\ISS\src\Services\EducationMaterial\getEducationMaterials\OutputDTO as MaterialDTO;

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
