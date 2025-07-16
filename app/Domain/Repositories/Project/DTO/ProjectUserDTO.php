<?php

namespace App\Domain\Repositories\Project\DTO;

use Carbon\Carbon;


final readonly class ProjectUserDTO
{
    /**
     * @param int $projectId
     * @param int $userId
     * @param array $roles
     * @param Carbon $invited
     * @param Carbon|null $joined
     * @param Carbon|null $left
     * @param int|null $id
     */
    public function __construct(
        public int     $projectId,
        public int     $userId,
        public array   $roles,
        public Carbon  $invited,
        public ?Carbon $joined = null,
        public ?Carbon $left = null,
        public ?int    $id = null,
    ) {}
}
