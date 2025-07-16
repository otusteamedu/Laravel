<?php

namespace App\TodoApp\Application\DTOs;

use Carbon\Carbon;
use App\TodoApp\Domain\ValueObjects\ProjectRoleEnum;


final readonly class ProjectInvitedUserDTO
{
    /**
     * @param int $id
     * @param int $userId
     * @param string $name
     * @param string $email
     * @param ProjectRoleEnum[] $roles
     * @param Carbon $invited
     * @param Carbon|null $joined
     */
    public function __construct(
        public int     $id,
        public int     $userId,
        public string  $name,
        public string  $email,
        public array   $roles,
        public Carbon  $invited,
        public ?Carbon $joined = null,
    ) {}
}
