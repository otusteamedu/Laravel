<?php

namespace App\Domain\Apartment\ValueObjects;

class Owner
{
    private string $name;

    public function __construct(string $name)
    {
        if (empty(trim($name))) {
            throw new \InvalidArgumentException('Не может быть пустым');
        }
        $this->name = $name;
    }

    public function toString(): string
    {
        return $this->name;
    }

    public function __toString(): string
    {
        return $this->name;
    }
}

