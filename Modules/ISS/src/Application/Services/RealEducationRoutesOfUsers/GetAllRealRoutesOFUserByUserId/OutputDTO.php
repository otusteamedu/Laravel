<?php

declare(strict_types=1);

namespace ISS\App\Application\Services\RealEducationRoutesOfUsers\GetAllRealRoutesOFUserByUserId;

/**
 * @var array<SingleRealRouteDTO> $routes массив раеальных маршрутов пользователя ИОС
 */

class OutputDTO
{
    public function __construct(
        public array $routes,
    )
    {
    }
}
