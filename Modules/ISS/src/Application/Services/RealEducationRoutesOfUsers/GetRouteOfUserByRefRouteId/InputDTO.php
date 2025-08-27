<?php

declare(strict_types=1);

namespace ISS\App\Application\Services\RealEducationRoutesOfUsers\GetRouteOfUserByRefRouteId;

/**
 * @var int $issUserId код пользователя ИОС
 * @var int $refRouteId код справочного маршрута
 */

class InputDTO
{
    public function __construct(
        public int $issUserId,
        public int $refRouteId
    )
    {
    }
}
