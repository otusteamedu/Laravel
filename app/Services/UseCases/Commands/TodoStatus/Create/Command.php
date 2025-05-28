<?php

namespace App\Services\UseCases\Commands\TodoStatus\Create;

final readonly class Command
{
    public function __construct(
        public int    $project_id,
        public string $name,
        public int    $sort,
        public string $color,
    ) {}
}
