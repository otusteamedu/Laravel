<?php

declare(strict_types=1);

namespace ISS\App\Application\Services\RealEducationRoutesOfUsers\GetRouteOfUserByRefRouteId;

/**
 * @var int $id код реального маршрута пользователя
 * @var int $userDataId код пользователя ИОС
 * @var int $routeId код справочного маршрута
 * @var int|null $lastPassPointId код крайней по дате реальной точки маршрута, для которой сдан экзамен
 */

class OutputDTO
{
    public function __construct(
        public int $id,
        public int $userDataId,
        public int $routeId,
        public int|null $lastPassPointId
    )
    {
    }
}
