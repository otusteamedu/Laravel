<?php

declare(strict_types=1);

namespace ISS\App\Application\Services\EducationMaterial\OpenFile;

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
