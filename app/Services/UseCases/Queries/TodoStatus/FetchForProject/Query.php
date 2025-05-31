<?php

namespace App\Services\UseCases\Queries\TodoStatus\FetchForProject;

final readonly class Query
{
    public function __construct(
        public int $projectId,
    ) {}
}
