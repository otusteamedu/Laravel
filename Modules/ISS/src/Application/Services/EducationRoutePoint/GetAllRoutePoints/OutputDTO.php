<?php

declare(strict_types=1);

namespace ISS\App\Application\Services\EducationRoutePoint\GetAllRoutePoints;

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
