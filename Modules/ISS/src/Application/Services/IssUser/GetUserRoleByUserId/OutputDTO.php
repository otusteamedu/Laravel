<?php

declare(strict_types=1);

namespace ISS\App\Application\Services\IssUser\GetUserRoleByUserId;

/**
 * @var int $roleId код роли пользователя в ИОС
 * @var string $roleName название роли пользователя в ИОС
 */

class OutputDTO
{
    public function __construct(
        public int $roleId,
        public string $roleName,
    )
    {
    }
}
