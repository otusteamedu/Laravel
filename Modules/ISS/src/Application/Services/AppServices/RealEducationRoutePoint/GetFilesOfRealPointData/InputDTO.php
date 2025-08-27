<?php

declare(strict_types=1);

namespace ISS\App\Application\Services\AppServices\RealEducationRoutePoint\GetFilesOfRealPointData;

/**
 * @var int $id код реальной точки обучающего маршрута
 */

class InputDTO
{
    public function __construct(
        public int $id
    )
    {
    }
}
