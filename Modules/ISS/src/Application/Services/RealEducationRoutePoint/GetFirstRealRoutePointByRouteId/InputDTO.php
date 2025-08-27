<?php

declare(strict_types=1);

namespace ISS\App\Application\Services\RealEducationRoutePoint\GetFirstRealRoutePointByRouteId;

/**
 * @var int $refRouteId код справочного маршрута
 */

class InputDTO
{
    public function __construct(
        public int $refRouteId,
    )
    {
    }
}
