<?php

declare(strict_types=1);

namespace App\Infrastructure\RefreshTokenHasher;

interface RefreshTokenHasherInterface
{
    /**
     * Хэширует переданную строку (например, refresh токен).
     */
    public function hash(string $token): string;

    /**
     * Проверяет, соответствует ли хэш оригинальному токену.
     */
    public function verify(string $token, string $hashedToken): bool;
}
