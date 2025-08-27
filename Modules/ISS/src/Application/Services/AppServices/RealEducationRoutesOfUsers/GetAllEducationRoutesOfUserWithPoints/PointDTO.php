<?php

declare(strict_types=1);

namespace ISS\App\Application\Services\AppServices\RealEducationRoutesOfUsers\GetAllEducationRoutesOfUserWithPoints;

/**
 * @var string $pass           состояние точки учебного маршрута для данного пользователя
 * @var string $examDate       дата экзамена для точки учебного маршрута
 * @var int $realRoutePointId  код реальной точки учебного маршрута
 * @var string $routePointName название точки учебного маршрута из справочника точек маршрутов
 */

class PointDTO
{
    public function __construct(
        public string $pass,
        public string $examDate,
        public int $realRoutePointId,
        public string $routePointName
    )
    {
    }
}
