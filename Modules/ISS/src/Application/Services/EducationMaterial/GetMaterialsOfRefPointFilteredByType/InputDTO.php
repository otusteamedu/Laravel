<?php

declare(strict_types=1);

namespace ISS\App\Application\Services\EducationMaterial\GetMaterialsOfRefPointFilteredByType;

/**
 * @var int $pointId код справочной точки учебного маршрута
 * @var string $type тип файлов учебного материала
 * @var array $returnedFields список возвращаемых полей
 */

class InputDTO
{
    public function __construct(
        public int $pointId,
        public string $type,
        public array $returnedFields = ['*'],
    )
    {
    }
}
