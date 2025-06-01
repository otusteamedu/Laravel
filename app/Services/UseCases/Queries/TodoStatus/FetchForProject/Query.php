<?php

namespace App\Services\UseCases\Queries\TodoStatus\FetchForProject;

final readonly class Query
{
    /**
     * @param int $projectId
     */
    public function __construct(
        public int $projectId,
    ) {}
}
