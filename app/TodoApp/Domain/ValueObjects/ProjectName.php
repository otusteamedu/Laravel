<?php

namespace App\TodoApp\Domain\ValueObjects;

final class ProjectName
{
    private string $value;

    /**
     * @param string $value
     */
    public function __construct(string $value)
    {
        $this->assertNameIsValid($value);
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
    private function assertNameIsValid(string $value): void
    {
        if (strlen($value) < 3 || strlen($value) > 255) {
            throw new \InvalidArgumentException("Название проекта должно быть строкой от 3-х до 255-ти знаков.");
        }
    }
}
