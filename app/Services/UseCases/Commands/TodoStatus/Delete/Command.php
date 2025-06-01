<?php

namespace App\Services\UseCases\Commands\TodoStatus\Delete;

final readonly class Command
{
    /**
     * @param int $id
     * @param int $projectId
     */
    public function __construct(
        public int $id,
        public int $projectId,
    ) {}
}
