<?php

namespace App\Services\Repositories\DTOs;

use App\Models\ProjectRoleEnum;
use Carbon\Carbon;


final readonly class ProjectInvitedUserDTO
{
    /**
     * @param int $id
     * @param int $user_id
     * @param string $name
     * @param string $email
     * @param ProjectRoleEnum[] $roles
     * @param Carbon $invited
     * @param Carbon|null $joined
     */
    public function __construct(
        public int     $id,
        public int     $user_id,
        public string  $name,
        public string  $email,
        public array   $roles,
        public Carbon  $invited,
        public ?Carbon $joined = null,
    ) {}
}
