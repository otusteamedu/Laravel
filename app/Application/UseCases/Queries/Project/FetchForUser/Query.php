<?php

namespace App\Application\UseCases\Queries\Project\FetchForUser;

final readonly class Query
{
    /**
     * @param int $userId
     */
    public function __construct(
        public int $userId,
    ) {}
}
