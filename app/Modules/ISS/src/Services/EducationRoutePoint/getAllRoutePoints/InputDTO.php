<?php

declare(strict_types=1);

namespace App\Modules\ISS\src\Services\EducationRoutePoint\getAllRoutePoints;

/**
 * @var array $returnedFields массив полей, которые хотим получить
 */

class InputDTO
{
    public function __construct(
        public array $returnedFields = ['*']
    )
    {
    }
}
