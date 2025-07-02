<?php

namespace App\Services\Exceptions\Users;

use Exception;

final class UserSaveException extends Exception
{
    public function __construct(string $message = "Не удалось сохранить пользователя")
    {
        parent::__construct($message);
    }
}
