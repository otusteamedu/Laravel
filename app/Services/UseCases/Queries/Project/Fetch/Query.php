<?php

namespace App\Services\UseCases\Queries\Project\Fetch;

final readonly class Query
{
    /**
     * @param int $projectId
     */
    public function __construct(
        public int $projectId,
    ) {}
}
