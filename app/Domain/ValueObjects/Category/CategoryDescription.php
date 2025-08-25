<?php

namespace App\Domain\ValueObjects\Category;

use App\Domain\Exceptions\NotValidItemDomainException;

class CategoryDescription
{
    private string $value;

    public function __construct(string $value) 
    {
        $value = trim($value);
        if ($value === '') {
            throw new NotValidItemDomainException('Описание категории не может быть пустым.');
        }
        $this->value = $value;
    }

    public function getValue(): string 
    {
        return $this->value;
    }
}
