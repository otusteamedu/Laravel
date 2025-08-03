<?php

declare(strict_types=1);

namespace App\Modules\ISS\src\Services\EducationMaterial\downloadFile;

use Symfony\Component\HttpFoundation\StreamedResponse as SymfonyStreamedResponse;

/**
 * @var SymfonyStreamedResponse $fileStream поток данных из файла
 */

class OutputDTO
{
    public function __construct(
        public SymfonyStreamedResponse $fileStream
    )
    {
    }
}
