<?php

declare(strict_types=1);

namespace ISS\App\Application\Services\RealEducationRoutesOfUsers\UpdateLastPassPoint;

/**
 * @var bool $operationResult результат обновления lpp (true -- обновил, false -- нет)
 */

class OutputDTO
{
    public function __construct(
        public bool $operationResult,
    )
    {
    }
}
