<?php

declare(strict_types=1);

namespace App\Modules\ISS\src\Services\issUser\getAllUsers;

/**
 * @var array<SingleUserDTO> $users массив данных пользователей ИОС
 */

class OutputDTO
{
    public function __construct(
        public array $users
    )
    {
    }
}
