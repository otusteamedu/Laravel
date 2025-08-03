<?php

declare(strict_types=1);

namespace App\Modules\ISS\src\Services\EducationRoutePoint\getAllRoutePoints;

/**
 * @var int $pointId код справочной точки обучающего маршрута
 * @var int $pointName название точки обучающего маршрута
 */

class SinglePointDTO
{
    public function __construct(
        public int $pointId,
        public string $pointName,
    )
    {
    }
}
