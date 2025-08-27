<?php

declare(strict_types=1);

namespace ISS\App\Application\Services\RealEducationRoutePoint\GetAllRealRoutePoints;

/**
 * @var int $id код реальной точки обучающего маршрута
 * @var int $routeId код справочного маршрута, к которому относится реальная точка маршрута
 * @var int $routePointId код справочной точки маршрута, к которой относится эта реальная точка маршрута
 * @var string $examDate запланированная жата экзамена для этой реальной точки маршрута
 * @var int $position позиция реальной точки маршрута на обучающем маршруте
 */

class OutputDTO
{
    public function __construct(
        public int $id,
        public int $routeId,
        public int $routePointId,
        public string $examDate,
        public int $position,
    )
    {
    }
}
