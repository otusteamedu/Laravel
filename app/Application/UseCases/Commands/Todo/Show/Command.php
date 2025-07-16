<?php

namespace App\Application\UseCases\Commands\Todo\Show;

final readonly class Command
{
    /**
     * @param int $projectId
     * @param int $todoId
     */
    public function __construct(
        public int $projectId,
        public int $todoId,
    ) {}
}
