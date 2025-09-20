<?php

namespace App\Domain\Factories\Area;

use App\Domain\BusinessModels\Area;
use App\Domain\ValueObjects\Area\AreaName;
use App\Domain\ValueObjects\Lang;

class AreaFactory
{
    public static function make(string $name, string $lang): Area
    {
        $areaName = new AreaName($name);
        $lang = new Lang($lang);

        return new Area($areaName, $lang);
    }
}
