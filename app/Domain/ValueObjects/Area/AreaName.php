<?php

namespace App\Domain\ValueObjects\Area;

use App\Domain\Exceptions\NotValidItemDomainException;

class AreaName 
{
    private string $value;

    public function __construct(string $value) 
    {
        $value = trim($value);
        if ($value === '') {
            throw new NotValidItemDomainException('Название территории не может быть пустым.');
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
