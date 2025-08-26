<?php

namespace App\Domain\ValueObjects\Product;

use App\Domain\Exceptions\NotValidItemDomainException;

class ProductDescription
{
    private string $value;

    public function __construct(string $value) 
    {
        $value = trim($value);
        if ($value === '') {
            throw new NotValidItemDomainException('Описание продукта не может быть пустым.');
        }
        $this->value = $value;
    }

    public function getValue(): string 
    {
        return $this->value;
    }
}
