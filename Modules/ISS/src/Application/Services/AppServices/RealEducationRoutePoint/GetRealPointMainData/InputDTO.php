<?php

declare(strict_types=1);

namespace ISS\App\Application\Services\AppServices\RealEducationRoutePoint\GetRealPointMainData;

/**
 * @var int $id         код реальной точки обучающего маршрута
 * @var int $userDataId код пользователя ИОС
 */

class InputDTO
{
    public function __construct(
        public int $id,
        public int $userDataId
    )
    {
    }
}
