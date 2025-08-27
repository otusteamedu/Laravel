<?php

declare(strict_types=1);

namespace ISS\App\Application\Services\RealEducationRoutePoint\GetNextRealRoutePointByPosition;

/**
 * @var int $routeId код справочного маршрута для реального обучающего маршрута
 * @var int $position позиция реальной точки на реальном маршруте (той для которой ищем следующую за ней точку)
 */

class InputDTO
{
    public function __construct(
        public int $routeId,
        public int $position,
    )
    {
    }
}
