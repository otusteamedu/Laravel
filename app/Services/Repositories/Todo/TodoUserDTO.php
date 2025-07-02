<?php

namespace App\Services\Repositories\Todo;

use App\Services\Repositories\Todo\TodoRoleEnum;

final readonly class TodoUserDTO
{
    /**
     * @param int $userId
     * @param string $name
     * @param string $email
     * @param TodoRoleEnum $role
     * @param int|null $id
     */
    public function __construct(
        public int          $userId,
        public string       $name,
        public string       $email,
        public TodoRoleEnum $role,
        public ?int         $id = null,
    ) {}
}
