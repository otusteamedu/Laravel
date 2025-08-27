<?php

declare(strict_types=1);

namespace ISS\App\Application\Services\Exam\ExamCheckCode\MakeCheckCode;

/**
 * @var int $issUserId         код пользователя ИОС, сдающего экзамен
 * @var int $realRoutePointId  код раельной точки обучающего маршрута
 */

class InputDTO
{
    public function __construct(
        public int $issUserId,
        public int $realRoutePointId,
    )
    {
    }
}
