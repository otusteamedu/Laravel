<?php

declare(strict_types=1);

namespace ISS\App\Application\Services\Exam\EducationQuestion\GetRefPointExamQuestions;

/**
 * @var int $id код справочной точки обучающего маршрута
 */

class InputDTO
{
    public function __construct(
        public int $id
    )
    {
    }
}
