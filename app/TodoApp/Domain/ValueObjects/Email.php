<?php

namespace App\TodoApp\Domain\ValueObjects;

final class Email
{
    private string $value;

    /**
     * @param string $value
     */
    public function __construct(string $value)
    {
        $this->assertEmailIsValid($value);
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
    private function assertEmailIsValid(string $value): void
    {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException("Не корректооное знечение email");
        }
    }
}
