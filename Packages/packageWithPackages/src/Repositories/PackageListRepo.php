<?php

namespace My\PackageWithPackages\Repositories;

use My\PackageWithPackages\Services\PackageList\PackageListRepoInterface;
use My\PackageWithPackages\Models\Package;

class PackageListRepo implements PackageListRepoInterface
{
    /**
     * Запрос БД получить все записи из таблицы Пакеты
     * @return array ['package_name' => , 'package_content' => ]
     */
    public function getAllPackages()
    {
        return Package::all()->toArray();
    }
}
