<?php

namespace App\Services\UseCases\Queries\TodoStatus\FetchForProject;

final readonly class TodoStatusDTO
{
    public function __construct(
        public int    $project_id,
        public string $name,
        public int    $sort,
        public string $color,
        public ?int   $id = null,
    ) {}
}
