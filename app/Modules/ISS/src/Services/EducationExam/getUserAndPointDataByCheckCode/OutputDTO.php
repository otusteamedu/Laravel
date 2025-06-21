<?php

declare(strict_types=1);

namespace App\Modules\ISS\src\Services\EducationExam\getUserAndPointDataByCheckCode;

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
