<?php

namespace App\Services\Queries\FetchAllTasks;

final readonly class Query
{
    public function __construct(
        public int $perPage = 10,
    ) {
    }
} 