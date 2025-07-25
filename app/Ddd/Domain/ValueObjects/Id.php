<?php

namespace App\Ddd\Domain\ValueObjects;

use Webmozart\Assert\Assert;

final readonly class Id
{
    private int $value;
    
    public function __construct(int $value) {
        Assert::greaterThan($value, 0, 'Идентификатор должен быть натуральным числом. Получено: %s');
        $this->value = $value;
    }

    public function toInt(): int
    {
        return $this->value;
    }
}