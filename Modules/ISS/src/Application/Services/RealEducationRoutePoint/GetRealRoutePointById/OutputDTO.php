<?php
declare(strict_types=1);

namespace ISS\App\Application\Services\RealEducationRoutePoint\GetRealRoutePointById;

/**
 * @var null|int $id код реальной точки обучающего маршрута
 * @var null|int $routePointId код справочной точки обучающего маршрута
 * @var null|int $routeId код маршрута, к которому относится реальная точка
 * @var null|string $examDate дата экзамена
 * @var null|int $position позиция в обучающем маршруте
 */

class OutputDTO
{
    public function __construct(
        public null|int $id,
        public null|int $routePointId,
        public null|int $routeId,
        public null|string $examDate,
        public null|int $position,
    )
    {
    }
}
