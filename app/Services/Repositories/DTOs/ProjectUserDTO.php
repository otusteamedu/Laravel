<?php

namespace App\Services\Repositories\DTOs;

use Carbon\Carbon;


final readonly class ProjectUserDTO
{
    /**
     * @param int $project_id
     * @param int $user_id
     * @param array $roles
     * @param Carbon $invited
     * @param Carbon|null $joined
     * @param Carbon|null $left
     * @param int|null $id
     */
    public function __construct(
        public int     $project_id,
        public int     $user_id,
        public array   $roles,
        public Carbon  $invited,
        public ?Carbon $joined = null,
        public ?Carbon $left = null,
        public ?int    $id = null,
    ) {}
}
