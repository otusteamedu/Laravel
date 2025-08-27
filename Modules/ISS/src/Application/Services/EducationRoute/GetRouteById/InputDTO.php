<?php

declare(strict_types=1);

namespace ISS\App\Application\Services\EducationRoute\GetRouteById;

/**
 * @var int $id код справочного маршрута
 */

class InputDTO
{
    public function __construct(
        public int $id,
    )
    {
    }
}
