<?php

namespace App\Domain\Apartment\ValueObjects;

class Owner
{
    private string $name;

    public function __construct(string $name)
    {
        if (trim($name) === '') {
            throw new \InvalidArgumentException('Не может быть пусто');
        }
        $this->name = $name;
    }

    public function __toString(): string
    {
        return $this->name;
    }
}
