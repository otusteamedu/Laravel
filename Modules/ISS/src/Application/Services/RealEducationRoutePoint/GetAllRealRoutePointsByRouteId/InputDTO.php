<?php

declare(strict_types=1);

namespace ISS\App\Application\Services\RealEducationRoutePoint\GetAllRealRoutePointsByRouteId;

/**
 * @var int $routeId код справочного маршрута, взятый из данных реального маршрута
 */

class InputDTO
{
    public function __construct(
        public int $routeId,
    )
    {
    }
}
