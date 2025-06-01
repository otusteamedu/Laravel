<?php

declare(strict_types=1);

namespace App\Modules\ISS\src\Services\EducationRoutePoint\getFilesOfRealPointData;

/**
 * @var int $id код реальной точки обучающего маршрута
 */

class InputDTO
{
    public function __construct(
        public int $id
    )
    {
    }
}
