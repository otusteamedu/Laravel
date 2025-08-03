<?php

declare(strict_types=1);

namespace App\Modules\ISS\src\Services\issUser\updateIssUser;


/**
 * @var int $id                     код пользователя ИОС
 * @var mixed $avatarFile           файл аватарки пользователя
 * @var int $userId                 код пользователя в основном приложении
 * @var string $roleName            наименование роли пользователя
 * @var string|null $organization   организация пользователя в основном приложении
 * @var string|null $name           имя пользователя из основного приложения
 * @var string|null $secondName     отвество пользователя из основного приложения
 * @var string|null $lastName       фамилия пользователя из основного приложения
 * @var string|null $email          email пользователя из основного приложения
 */

class InputDTO
{
    public function __construct(
        public int|null    $id,
        public mixed       $avatarFile,
        public int         $userId,
        public string      $roleName,
        public string|null $organization,
        public string|null $name,
        public string|null $secondName,
        public string|null $lastName,
        public string|null $email,
    )
    {
    }
}
