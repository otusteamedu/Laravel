<?php

declare(strict_types=1);

namespace ISS\App\Application\Services\RealEducationRoutesOfUsers\GetAllRealRoutesByRefRouteId;

/**
 * @var int $id код реального обучающего маршрута
 * @var int $refRouteId код справочного маршрута к которому относится текущий реальный маршрут
 * @var int $issUserId код пользователя ИОС
 * @var int $lastPassedPointId код последней пройденной реальной точки обучающего маршрута
 */

class OutputDTO
{
    public function __construct(
        public int $id,
        public int $refRouteId,
        public int $issUserId,
        public int $lastPassedPointId,
    )
    {
    }
}
