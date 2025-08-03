<?php

declare(strict_types=1);

namespace App\Modules\ISS\src\Services\EducationRoutePoint\getPointData;

/**
 * @var int $id код справочной точки обучающего маршрута
 */

class InputDTO
{
    public function __construct(
        public int $id,
    )
    {
    }
}
