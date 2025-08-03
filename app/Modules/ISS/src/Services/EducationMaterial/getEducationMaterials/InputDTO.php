<?php

declare(strict_types=1);

namespace App\Modules\ISS\src\Services\EducationMaterial\getEducationMaterials;

/**
 * @var int $pointId код справочной точки учебного маршрута
 * @var array $returnedFields список возвращаемых полей
 */

class InputDTO
{
    public function __construct(
        public int $pointId,
        public array $returnedFields = ['*'],
    )
    {
    }
}
