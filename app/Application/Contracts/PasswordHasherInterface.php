<?php

declare(strict_types=1);

namespace App\Application\Contracts;

interface PasswordHasherInterface
{
    /**
     * Хеширует пароль
     *
     * @param string $password
     * @return string
     */
    public function hash(string $password): string;

    /**
     * Проверяет соответствие пароля хешу
     *
     * @param string $password
     * @param string $hash
     * @return bool
     */
    public function check(string $password, string $hash): bool;
}
