<?php

declare(strict_types=1);

namespace App\Modules\ISS\src\Services\EducationExam\isExamCanBePassed;

/**
 * @var int $issUserId        код пользователя ИОС, сдающего экзамен
 * @var int $realRoutePointId код реальной точки обучающего маршрута
 */

class InputDTO
{
    public function __construct(
        public int $issUserId,
        public int $realRoutePointId
    )
    {
    }
}
