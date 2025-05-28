<?php

namespace App\Services\UseCases\Queries\Project\FetchWithRelations;

final readonly class TodoStatusDTO
{
    public function __construct(
        public int $id,
        public string $name,
        public string $color,
        public int $sort,
    ) {}
}
