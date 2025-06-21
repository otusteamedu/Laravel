<?php

namespace App\Services\UseCases\Queries\Todo\Fetch;

final readonly class Query
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
