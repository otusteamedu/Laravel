<?php

namespace App\Services\Repositories;

final readonly class TodoStatusDTO
{
    public function __construct(
        public ?int   $id,
        public int    $project_id,
        public string $name,
        public int    $sort,
        public string $color,
    ) {}
}
