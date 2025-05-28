<?php

namespace App\Services\UseCases\Queries\Project\FetchWithRelations;

final readonly class Query
{
    public function __construct(
        public int $projectId,
    ) {}
}
