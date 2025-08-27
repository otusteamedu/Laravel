<?php

declare(strict_types=1);

namespace ISS\App\Application\Services\EducationRoutePoint\GetRoutePointById;

/**
 * @var int $id код справочной точки маршрута
 */

class InputDTO
{
    public function __construct(
        public int $id,
    )
    {
    }
}
