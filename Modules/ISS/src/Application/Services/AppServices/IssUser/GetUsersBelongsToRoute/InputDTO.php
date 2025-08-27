<?php

declare(strict_types=1);

namespace ISS\App\Application\Services\AppServices\IssUser\GetUsersBelongsToRoute;

/**
 * @var int $routeId код справочного маршрута, относящегося к реальному маршруту
 */

class InputDTO
{
    public function __construct(
        public int $routeId,
    )
    {
    }
}
