<?php

namespace App\Domain\ValueObjects\MeasureProductRecipe;

use App\Domain\Exceptions\NotValidItemDomainException;

class MeasureProductRecipeValue 
{
    private string $value;

    public function __construct(string|int $value) 
    {
        $this->value = $value;
    }

    public function getValue(): string 
    {
        return $this->value;
    }
}
