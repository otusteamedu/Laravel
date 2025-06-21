<?php

declare(strict_types=1);

namespace App\Modules\ISS\src\Services\EducationMeterial\downloadFile;

/**
 * @var string $fileType тип файла учебного материала
 * @var string $fileName имя файла учебного материала
 */

class InputDTO
{
    public function __construct(
        public string $fileType,
        public string $fileName
    )
    {
    }
}
