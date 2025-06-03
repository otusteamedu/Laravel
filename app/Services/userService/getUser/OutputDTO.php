<?php

namespace App\Services\userService\getUser;

/**
 * @var string $id                 код пользователя Основного приложения
 * @var string|null $name          имя пользователя Основного приложения
 * @var string|null $secondName    отчество пользователя Основного приложения
 * @var string|null $lastName      фамилия пользователя Основного приложения
 * @var string|null $organization  название организации пользователя Основного приложения
 * @var string $userRole           роль пользователя Основного приложения
 */

class OutputDTO
{
    public function __construct(
        public string $id,
        public string|null $name,
        public string|null $secondName,
        public string|null $lastName,
        public string|null $organization,
        public string $userRole
    )
    {
    }
}
