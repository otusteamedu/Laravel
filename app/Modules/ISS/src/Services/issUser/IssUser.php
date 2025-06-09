<?php

namespace App\Modules\ISS\src\Services\issUser;

/**
 * Объект данного класса создается при входе пользователя в ИОС.
 * Объект данного класса хранит данные текущего пользователя и находится в сессии.
 *
 * @var int|null $issUserId        код пользователя ИОС
 * @var string|null $issUserRole   название роли пользователя иос (из таблицы user_roles)
 * @var string|null $issUserAvatar путь к файлу аватарки пользователя
 * @var string|null $organization  организация пользователя в основном приложении
 * @var string|null $name          имя пользователя из основного приложения
 * @var string|null $secondName   отвество пользователя из основного приложения
 * @var string|null $lastName     фамилия пользователя из основного приложения
 * @var string|null $webToken          защитный токен для авторизованного пользователя ИОС
 */

class IssUser
{
    public function __construct(
        public int|null    $issUserId = null,
        public string|null $issUserRole = null,
        public string|null $issUserAvatar = null,
        public string|null $organization = null,
        public string|null $name = null,
        public string|null $secondName = null,
        public string|null $lastName = null,
        public string|null $webToken = null,
    )
    {
    }
}
