<?php

namespace App\TodoApp\Domain\ValueObjects;

final class UserProfile
{
    private ?string $biography;
    private ?int   $telegramId;

    public function __construct(
        ?string $biography = null,
        ?int    $telegramId = null,
    ) {
        $this->assertIsValidTelegramId($telegramId);

        $this->biography = $biography;
        $this->telegramId = $telegramId;
    }

    private function assertIsValidTelegramId($value)
    {
        if ($value <= 0) {
            throw new \InvalidArgumentException("Идентификатор пользователя telegram должен быть натуральным числом");
        }
    }

    /**
     * Get the value of biography
     */
    public function getBiography()
    {
        return $this->biography;
    }

    /**
     * Get the value of telegram_id
     */
    public function getTelegramId()
    {
        return $this->telegramId;
    }
}
