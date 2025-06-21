<?php

declare(strict_types=1);

namespace App\Modules\ISS\src\Services\EducationRoutePoint\getFilesOfRealPointData;

/**
 * @var array<string,array[][]> $materials массив учебных материалов ['тип материала' => материалы_данного_типа[[], [], ...]]
 */

class OutputDTO
{
    public function __construct(
        public array $materials
    )
    {
    }
}
