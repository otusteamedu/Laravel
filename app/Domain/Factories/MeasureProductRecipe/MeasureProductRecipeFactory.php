<?php

namespace App\Domain\Factories\MeasureProductRecipe;

use App\Domain\BusinessModels\Measure;
use App\Domain\BusinessModels\MeasureProductRecipe;
use App\Domain\BusinessModels\Product;
use App\Domain\BusinessModels\Recipe;
use App\Domain\ValueObjects\MeasureProductRecipe\MeasureProductRecipeValue;

class MeasureProductRecipeFactory
{
    public static function make(
        Recipe $recipe,
        Product $product,
        Measure $measure,
        ?string $value = null
    ): MeasureProductRecipe {
        $value = new MeasureProductRecipeValue($value);

        return new MeasureProductRecipe(
            $recipe,
            $product,
            $measure,
            $value
        );
    }
}
