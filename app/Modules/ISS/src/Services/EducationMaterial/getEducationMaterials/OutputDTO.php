<?php

declare(strict_types=1);

namespace App\Modules\ISS\src\Services\EducationMaterial\getEducationMaterials;

/**
 * @var null|int $id код учебного материала
 * @var null|string $tiitle название учебного материала
 * @var null|string $filePath путь к файлу учебного материала (реально имя файла, а папка выбирается через тип материала)
 * @var null|string $typeName название типа учебного материала
 * @var null|int $typeId код типа учебного материала
 */

class OutputDTO
{
    public function __construct(
        public null|int $id,
        public null|string $tiitle,
        public null|string $filePath,
        public null|string $typeName,
        public null|int $typeId,
    )
    {
    }
}
