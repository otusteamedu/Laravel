<?php

namespace App\TodoApp\Domain\ValueObjects;

final class Color
{
    private string $value;

    /**
     * @param string $value
     */
    public function __construct(string $value)
    {
        $this->assertColorIsValid($value);
        $this->value = $value;
    }

    /**
     * Get the value of name
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @param string $value
     * @throws \InvalidArgumentException
     * @return void
     */
    private function assertColorIsValid(string $value): void
    {
        if (!preg_match("/^#[a-f0-9]{6}$/i", $value)) {
            throw new \InvalidArgumentException("Значение атрибута color должно быть строкой в формате HEX");
        }
    }
}
