<?php

namespace App\Domain\Factories\Measure;

use App\Domain\BusinessModels\Measure;
use App\Domain\ValueObjects\Measure\MeasureName;
use App\Domain\ValueObjects\Lang;

class MeasureFactory
{
    public static function make(string $name, string $lang): Measure
    {
        $measureName = new MeasureName($name);
        $lang = new Lang($lang);

        return new Measure($measureName, $lang);
    }
}
