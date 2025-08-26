<?php

namespace App\Domain\ValueObjects\Product;

use App\Domain\Exceptions\NotValidItemDomainException;

class ProductName 
{
    private string $value;

    public function __construct(string $value) 
    {
        $value = trim($value);
        if ($value === '') {
            throw new NotValidItemDomainException('Название продукта не может быть пустым.');
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
