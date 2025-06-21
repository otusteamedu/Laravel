<?php

namespace App\Services\UseCases\Commands\Todo\Delete;

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
