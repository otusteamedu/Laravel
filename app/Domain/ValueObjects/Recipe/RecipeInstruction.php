<?php

namespace App\Domain\ValueObjects\Recipe;

use App\Domain\Exceptions\NotValidItemDomainException;

class RecipeInstruction 
{
    private string $value;

    public function __construct(string $value) 
    {
        $this->value = $value;
    }

    public function getValue(): string 
    {
        return $this->value;
    }
}
