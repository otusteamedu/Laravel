<?php

namespace App\Application\UseCases\Commands\TodoStatus\Delete;

final readonly class Command
{
    /**
     * @param int $statusId
     * @param int $projectId
     */
    public function __construct(
        public int $statusId,
        public int $projectId,
    ) {}
}
