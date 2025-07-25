<?php

namespace App\Ddd\Domain\ValueObjects;

use Webmozart\Assert\Assert;

final readonly class Uid
{
    private string $value;
    
    public function __construct(string $value) {
        Assert::regex($value, '/^[a-z0-9-]{36}$/', 'Некорректный uid');
        $this->value = $value;
    }

    public function toString(): string
    {
        return $this->value;
    }
}