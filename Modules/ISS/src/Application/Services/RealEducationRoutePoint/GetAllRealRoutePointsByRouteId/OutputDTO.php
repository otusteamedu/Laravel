<?php

declare(strict_types=1);

namespace ISS\App\Application\Services\RealEducationRoutePoint\GetAllRealRoutePointsByRouteId;

use ISS\App\Application\Services\RealEducationRoutePoint\GetAllRealRoutePointsByRouteId\SingleRealPointDTO;

/**
 * @var array<SingleRealPointDTO> $realPoints массив данных реальных точек учебного маршрута
 */

class OutputDTO
{
    public function __construct(
        public array $realPoints,
    )
    {
    }
}
