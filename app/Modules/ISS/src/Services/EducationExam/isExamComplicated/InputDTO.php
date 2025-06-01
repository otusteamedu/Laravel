<?php

declare(strict_types=1);

namespace App\Modules\ISS\src\Services\EducationExam\isExamComplicated;

/**
 * @var int $id код реальной точки маршрута
 */

class InputDTO
{
    public function __construct(
        public int $id
    )
    {
    }
}
