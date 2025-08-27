<?php

declare(strict_types=1);

namespace ISS\App\Application\Services\EducationRoute\GetRouteById;

/**
 * @var int $routeId код справочного маршрута
 * @var string $routeName название справочного маршрута
 */

class OutputDTO
{
    public function __construct(
        public int $routeId,
        public string $routeName
    )
    {
    }
}
