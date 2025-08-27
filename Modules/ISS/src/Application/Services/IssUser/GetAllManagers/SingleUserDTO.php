<?php

declare(strict_types=1);

namespace ISS\App\Application\Services\IssUser\GetAllManagers;

/**
 * @var int|null $id                код пользователя ИОС
 * @var string|null $avatarFilePath путь к файлу аватарки пользователя
 * @var int|null $userId            код пользователя в основном приложении
 * @var int|null $roleId            код роли пользователя ИОС
 * @var string|null $roleName       наименование роли пользователя
 * @var string|null $organization   организация пользователя в основном приложении
 * @var string|null $name           имя пользователя из основного приложения
 * @var string|null $secondName     отвество пользователя из основного приложения
 * @var string|null $lastName       фамилия пользователя из основного приложения
 * @var string|null $email          почта пользователя из основного приложения
 * @var string|null $createdAt
 * @var string|null $updatedAt
 * @var string|null $deletedAt
 */

class SingleUserDTO
{
    public function __construct(
        public int|null    $id,
        public string|null $avatarFilePath,
        public int|null    $userId,
        public int|null    $roleId,
        public string|null $roleName,
        public string|null $organization,
        public string|null $name,
        public string|null $secondName,
        public string|null $lastName,
        public string|null $email,
        public string|null $createdAt,
        public string|null $updatedAt,
        public string|null $deletedAt
    )
    {
    }
}
