<?php

declare(strict_types=1);

namespace App\Modules\ISS\src\Services\NotifyService\getDataForExamStatusNotify;

/**
 * @var int $issUserId         код пользователя ИОС, которого оповещаем на счет экзамена
 * @var int $realRoutePointId  код реальной точки маршрута для которой рассматривеам статус экзамена
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
