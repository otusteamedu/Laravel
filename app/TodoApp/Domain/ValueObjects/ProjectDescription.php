<?php

namespace App\TodoApp\Domain\ValueObjects;

final class ProjectDescription
{
    private ?string $value;

    /**
     * @param string|null $value
     */
    public function __construct(?string $value = null)
    {
        $this->value = $value;
    }

    /**
     * Get the value of name
     */
    public function getValue(): string
    {
        return $this->value;
    }
}
