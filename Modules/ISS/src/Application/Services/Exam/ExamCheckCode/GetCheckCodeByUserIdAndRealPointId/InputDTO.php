<?php

declare(strict_types=1);

namespace ISS\App\Application\Services\Exam\ExamCheckCode\GetCheckCodeByUserIdAndRealPointId;

/**
 * @var int $issUserId        код пользователя ИОС, отправившего бланк экзамена на проверку
 * @var int $realRoutePointId код реальной точки обучающего маршрута, для которой сдается бланк экзамена
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
