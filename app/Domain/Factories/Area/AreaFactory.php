<?php

namespace App\Domain\Factories\Area;

use App\Domain\BusinessModels\Area;
use App\Domain\ValueObjects\Area\AreaName;
use App\Domain\ValueObjects\Area\AreaLang;

class AreaFactory
{
    public static function make(string $name, string $lang): Area
    {
        $areaName = new AreaName($name);
        $areaLang = new AreaLang($lang);

        return new Area($areaName, $areaLang);
    }
}
