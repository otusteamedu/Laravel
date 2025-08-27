<?php

declare(strict_types=1);

namespace ISS\App\Application\Services\RealEducationRoutesOfUsers\DeleteAllEducationRoutesOfIssUser;

/**
 * @var bool $operationResult результат удаления (true --удалено \ false -- нет)
 */

class OutputDTO
{
    public function __construct(
        public bool $operationResult,
    )
    {
    }
}
