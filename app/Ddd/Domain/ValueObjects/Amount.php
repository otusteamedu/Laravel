<?php

namespace App\Ddd\Domain\ValueObjects;

use Webmozart\Assert\Assert;

final readonly class Amount
{
    private int $value;
    
    public function __construct(int $value) {
        Assert::greaterThan($value, 0, 'Стоимость должна быть натуральным числом. Получено: %s');
        $this->value = $value;
    }

    public function toInt(): int
    {
        return $this->value;
    }
}