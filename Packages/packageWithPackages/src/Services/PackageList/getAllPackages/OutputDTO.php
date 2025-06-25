<?php

declare(strict_types=1);

namespace My\PackageWithPackages\Services\PackageList\getAllPackages;

use My\PackageWithPackages\Services\PackageList\getAllPackages\PackageDTO;

/**
 * @var array<PackageDTO> $packages пакеты
 */

class OutputDTO
{
    public function __construct(
        public array $packages,
    )
    {
    }
}
