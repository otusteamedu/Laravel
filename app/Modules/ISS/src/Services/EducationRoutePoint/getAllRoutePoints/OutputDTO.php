<?php

declare(strict_types=1);

namespace App\Modules\ISS\src\Services\EducationRoutePoint\getAllRoutePoints;

/**
 * @var array<SinglePointDTO> $routePoints массив справочных точек обучающих маршрутов
 */

class OutputDTO
{
    public function __construct(
        public array $routePoints,
    )
    {
    }
}
