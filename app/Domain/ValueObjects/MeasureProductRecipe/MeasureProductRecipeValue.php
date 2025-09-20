<?php

namespace App\Domain\ValueObjects\MeasureProductRecipe;

use App\Domain\Exceptions\NotValidItemDomainException;

class MeasureProductRecipeValue 
{
    private ?string $value = null;

    public function __construct(?string $value = null) 
    {
        $this->value = $value;
    }

    public function getValue(): ?string 
    {
        return $this->value;
    }
}
