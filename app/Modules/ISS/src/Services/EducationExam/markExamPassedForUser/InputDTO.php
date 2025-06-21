<?php

declare(strict_types=1);

namespace App\Modules\ISS\src\Services\EducationExam\markExamPassedForUser;

/**
 * @var int $issUserId код пользователя ИОС, который сдал экзамен
 * @var int $realRoutePointId код реальной точки маршрута, для которой пользователь сдал экзамен
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
