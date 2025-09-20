<?php

namespace App\Domain\Factories\Recipe;

use App\Domain\BusinessModels\Area;
use App\Domain\BusinessModels\Category;
use App\Domain\BusinessModels\Recipe;
use App\Domain\ValueObjects\Recipe\RecipeInstruction;
use App\Domain\ValueObjects\Recipe\RecipeName;
use App\Domain\ValueObjects\Lang;
use App\Domain\ValueObjects\Recipe\RecipeArea;
use App\Domain\ValueObjects\Recipe\RecipeCategory;

class RecipeFactory
{
    public static function make(
        string $name,
        string $instruction,
        string $lang,
        ?string $apiId = null,
        ?string $alternate = null,
        ?Category $category = null,
        ?Area $area = null,
    ): Recipe {
        $recipeName = new RecipeName($name);
        $recipeInstruction = new RecipeInstruction($instruction);
        $lang = new Lang($lang);

        return new Recipe(
            $recipeName, 
            $recipeInstruction, 
            $lang,
            $apiId,
            $alternate,
            $category,
            $area
        );
    }
}
