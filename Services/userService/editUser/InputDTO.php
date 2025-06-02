<?php

namespace App\Services\userService\editUser;

/**
 * @var int $userId        код пользователя Основного приложения
 * @var string $lastName   фамилия пользователя Основного приложения
 * @var string $name       имя пользователя Основного приложения
 * @var string $secondName отчество пользователя Основного приложения
 */

class InputDTO
{
    public function __construct(
        public int $userId,
        public string $lastName,
        public string $name,
        public string $secondName
    )
    {
    }
}
