<?php

namespace App\Application\UseCases\Commands\Todo\Delete;

final readonly class Command
{
    /**
     * @param int $todoId
     * @param int $projectId
     */
    public function __construct(
        public int $todoId,
        public int $projectId,
    ) {}
}
