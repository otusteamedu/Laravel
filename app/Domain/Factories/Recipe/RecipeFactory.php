<?php

namespace App\Domain\Factories\Recipe;

use App\Domain\BusinessModels\Recipe;
use App\Domain\ValueObjects\Recipe\RecipeInstruction;
use App\Domain\ValueObjects\Recipe\RecipeName;
use App\Domain\ValueObjects\Lang;

class RecipeFactory
{
    public static function make(
        string $name,
        string $instruction,
        string $lang
    ): Recipe {
        $recipeName = new RecipeName($name);
        $recipeInstruction = new RecipeInstruction($instruction);
        $lang = new Lang($lang);

        return new Recipe($recipeName, $recipeInstruction, $lang);
    }
}
