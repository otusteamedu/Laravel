<?php

namespace My\PackageWithPackages\Services\PackageList\getAllPackages;

use My\PackageWithPackages\Services\PackageList\PackageListRepoInterface;
use My\PackageWithPackages\Services\PackageList\getAllPackages\InputDTO;
use My\PackageWithPackages\Services\PackageList\getAllPackages\PackageDTO;
use My\PackageWithPackages\Services\PackageList\getAllPackages\OutputDTO;
use My\PackageWithPackages\Services\PackageList\getAllPackages\PackageListException;

class GetAllPackages
{
    private $repo;

    public function __construct(
        PackageListRepoInterface $packageListRepoInterface
    )
    {
        $this->repo = $packageListRepoInterface;
    }

    /**
     * Извлечение всех пакетов
     * @param InputDTO $inputDTO
     * @return OutputDTO
     * @throws PackageListException
     */
    public function __invoke(InputDTO $inputDTO): OutputDTO
    {
        try {
            $result = $this->repo->getAllPackages();
        } catch (\Error | \Exception $e) {
            throw new PackageListException();
        }

        $packagesArr =  array_map(
            function ($package) {
               return new PackageDTO(
                    packageName: $package['package_name'],
                    packageContent: $package['package_content']);
            },
            $result
        );

        return new OutputDTO(packages: $packagesArr);
    }
}
