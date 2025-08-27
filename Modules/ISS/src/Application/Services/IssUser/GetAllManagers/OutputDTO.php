<?php

declare(strict_types=1);

namespace ISS\App\Application\Services\IssUser\GetAllManagers;

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
