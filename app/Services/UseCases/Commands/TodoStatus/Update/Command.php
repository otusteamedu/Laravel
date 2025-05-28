<?php

namespace App\Services\UseCases\Commands\TodoStatus\Update;

final readonly class Command
{
    public function __construct(
        public int    $id,
        public int    $project_id,
        public string $name,
        public int    $sort,
        public string $color,
    ) {}
}
