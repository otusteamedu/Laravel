<?php

namespace App\TodoApp\Application\UseCases\Queries\Project\FetchUsers;

final readonly class Query
{
    /**
     * @param int $projectId
     */
    public function __construct(
        public int $projectId,
    ) {}
}
