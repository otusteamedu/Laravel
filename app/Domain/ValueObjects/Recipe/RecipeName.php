<?php

namespace App\Domain\ValueObjects\Recipe;

use App\Domain\Exceptions\NotValidItemDomainException;

class RecipeName 
{
    private string $value;

    public function __construct(string $value) 
    {
        $value = trim($value);
        if ($value === '') {
            throw new NotValidItemDomainException('Название рецепта не может быть пустым.');
        }
        if (mb_strlen($value) > 255) {
            throw new NotValidItemDomainException('Название слишком длинное');
        }
        $this->value = $value;
    }

    public function getValue(): string 
    {
        return $this->value;
    }
}
