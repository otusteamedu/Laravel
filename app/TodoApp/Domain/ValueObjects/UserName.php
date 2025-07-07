<?php

namespace App\TodoApp\Domain\ValueObjects;

final class UserName
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
        if (!preg_match("/^[A-Za-zА-ЯЁа-яё]+(?:[ _-][A-Za-zА-ЯЁа-яё]+)*$/", $value)) {
            throw new \InvalidArgumentException("Не корректооное имя пользователя " . $value);
        }
    }
}
