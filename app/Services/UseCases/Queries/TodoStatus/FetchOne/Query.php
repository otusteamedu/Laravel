<?php

namespace App\Services\UseCases\Queries\TodoStatus\FetchOne;

final readonly class Query
{
    /**
     * @param int $projectId
     */
    public function __construct(
        public int $projectId,
        public int $statusId,
    ) {}
}
