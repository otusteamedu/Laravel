<?php

declare(strict_types=1);

namespace ISS\App\Application\Services\RealEducationRoutesOfUsers\GetAllRealRoutesOFUserByUserId;

/**
 * @var int $id код реального обучающего маршрута мользователя
 * @var int $userDataId код пользователя ИОС
 * @var int $routeId код справочного маршрута для этого реального маршрута
 * @var int|null $lastPassPointId код последней пройденной точки на этом маршруте пользователя ИОС
 */

class SingleRealRouteDTO
{
    public function __construct(
        public int $id,
        public int $userDataId,
        public int $routeId,
        public int|null $lastPassPointId,
    )
    {
    }
}
