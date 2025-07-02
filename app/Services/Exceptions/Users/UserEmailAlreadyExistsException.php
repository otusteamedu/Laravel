<?php

namespace App\Services\Exceptions\Users;

use Exception;

final class UserEmailAlreadyExistsException extends Exception
{
    public function __construct(string $email)
    {
        parent::__construct("Пользователь с email '{$email}' уже существует");
    }
}
