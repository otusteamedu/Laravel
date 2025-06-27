<?php

declare(strict_types=1);

namespace My\PackageWithPackages\Services\PackageList\getAllPackages;

/**
 * @var string $packageName имя пакета
 * @var string $packageContent слдержание пакета
 * @var
 */

class PackageDTO
{
    public function __construct(
        public string $packageName,
        public string $packageContent,
    )
    {
    }
}
