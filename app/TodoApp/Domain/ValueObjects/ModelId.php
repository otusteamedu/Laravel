<?php

namespace App\TodoApp\Domain\ValueObjects;

final class ModelId
{
    private int $value;

    /**
     * @param integer $value
     */
    public function __construct(int $value)
    {
        $this->assertIdIsValid($value);
        $this->value = $value;
    }

    /**
     * Get the value of value
     */
    public function getValue(): int
    {
        return $this->value;
    }

    /**
     * @param int $value
     * @throws \InvalidArgumentException
     * @return void
     */
    private function assertIdIsValid(int $value): void
    {
        if ($value <= 0) {
            throw new \InvalidArgumentException("Идентификатор должен быть натуральным числом");
        }
    }
}
