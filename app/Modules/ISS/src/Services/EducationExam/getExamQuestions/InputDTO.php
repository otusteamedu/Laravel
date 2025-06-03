<?php

declare(strict_types=1);

namespace App\Modules\ISS\src\Services\EducationExam\getExamQuestions;

/**
 * @var int $id код реальной точки учебного маршрута
 */

class InputDTO
{
    public function __construct(
        public int $id
    )
    {
    }
}
