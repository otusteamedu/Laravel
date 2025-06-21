<?php

namespace App\Services\UseCases\Queries\Todo\FetchForProject;

final readonly class Query
{
    /**
     * @param int $projectId
     * @param int|null $userId

     */
    public function __construct(
        public int $projectId,
        public ?int $userId = null
    ) {}
}
