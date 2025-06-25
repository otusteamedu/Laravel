<?php

namespace My\PackageWithPackages\Services\PackageList;

interface PackageListRepoInterface
{
    /**
     * Запрос БД получить все записи из таблицы Пакеты
     * @return array ['package_name' => , 'package_content' => ]
     */
    public function getAllPackages();
}
