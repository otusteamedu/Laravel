<?php

namespace App\TodoApp\Application\DTOs;

use DateTime;

final readonly class ProjectUserDTO
{
    /**
     * @param int $projectId
     * @param int $userId
     * @param array $roles
     * @param DateTime $invited
     * @param DateTime|null $joined
     * @param DateTime|null $left
     */
    public function __construct(
        public int       $projectId,
        public int       $userId,
        public array     $roles,
        public DateTime  $invited,
        public ?DateTime $joined = null,
        public ?DateTime $left = null,
    ) {}
}
