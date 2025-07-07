<?php

namespace App\TodoApp\Application\DTOs;

final readonly class TodoStatusDTO
{
    /**
     * @param int $projectId
     * @param string $name
     * @param int $sort
     * @param string $color
     * @param int|null $statusId
     */
    public function __construct(
        public int    $projectId,
        public string $name,
        public int    $sort,
        public string $color,
        public ?int   $statusId = null,
    ) {}
}
