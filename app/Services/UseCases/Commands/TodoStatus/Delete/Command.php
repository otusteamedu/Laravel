<?php

namespace App\Services\UseCases\Commands\TodoStatus\Delete;

final readonly class Command
{
    public function __construct(
        public int $id,
        public int $projectId,
    ) {}
}
