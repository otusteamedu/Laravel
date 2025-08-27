<?php

declare(strict_types=1);

namespace ISS\App\Application\Services\IssUser\GetAllUsersByOrganization;

/**
 * @var array<SingleUserDTO> $users массив данных объектов пользователей
 */

class OutputDTO
{
    public function __construct(
        public array $users,
    )
    {
    }
}
