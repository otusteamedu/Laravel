<?php

namespace App\Services\userService\getUser;

/**
 * @var int $userId код пользователя Основного приложения
 */

class InputDTO
{
    public function __construct(
        public int $userId,
    )
    {
    }
}
