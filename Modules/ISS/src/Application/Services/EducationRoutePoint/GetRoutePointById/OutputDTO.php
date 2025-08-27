<?php

declare(strict_types=1);

namespace ISS\App\Application\Services\EducationRoutePoint\GetRoutePointById;

/**
 * @var int $pointId код справочной точки маршрута
 * @var string $pointName название справочной точки маршрута
 */

class OutputDTO
{
    public function __construct(
        public int $pointId,
        public string $pointName
    )
    {
    }
}
