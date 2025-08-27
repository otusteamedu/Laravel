<?php

declare(strict_types=1);

namespace ISS\App\Application\Services\Exam\ExamCheckCode\GetUserAndPointDataByCheckCode;

/**
 * @var int $issUserId        код пользователя ИОС, отправившего бланк экзамена на проверку
 * @var int $realRoutePointId код реальной точки обучающего маршрута, для которой сдается бланк экзамена
 */

class OutputDTO
{
    public function __construct(
        public int $issUserId,
        public int $realRoutePointId
    )
    {
    }
}
