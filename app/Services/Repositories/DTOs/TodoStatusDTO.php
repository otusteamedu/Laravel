<?php

namespace App\Services\Repositories\DTOs;

final readonly class TodoStatusDTO
{
    /**
     * @param int $project_id
     * @param string $name
     * @param int $sort
     * @param string $color
     * @param int|null $id
     */
    public function __construct(
        public int    $project_id,
        public string $name,
        public int    $sort,
        public string $color,
        public ?int   $id = null,
    ) {}
}
