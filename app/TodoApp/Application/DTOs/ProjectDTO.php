<?php

namespace App\TodoApp\Application\DTOs;

use DateTime;

final readonly class ProjectDTO
{
    /**
     * @param int $projectId
     * @param string $name
     * @param string|null $description
     * @param DateTime $created
     */
    public function __construct(
        public int       $projectId,
        public string    $name,
        public ?string   $description = null,
        public DateTime $created,
    ) {}
}
