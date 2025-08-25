<?php

namespace App\Domain\Factories\MeasureProductRecipe;

use App\Domain\BusinessModels\MeasureProductRecipe;
use App\Domain\ValueObjects\MeasureProductRecipe\MeasureProductRecipeValue;

class MeasureProductRecipeFactory
{
    public static function make(string|int $value): MeasureProductRecipe
    {
        $value = new MeasureProductRecipeValue($value);

        return new MeasureProductRecipe($value);
    }
}
