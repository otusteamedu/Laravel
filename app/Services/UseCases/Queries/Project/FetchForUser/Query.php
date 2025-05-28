<?php

namespace App\Services\UseCases\Queries\Project\FetchForUser;

final readonly class Query
{
    public function __construct(
        public int $userId,
    ) {}
}
