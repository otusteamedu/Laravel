<?php

namespace App\Services\UseCases\Queries\FetchAllUsersPagination;

final readonly class Query
{
    public function __construct(
        public int $limit = 10,
        public int $offset = 0,
    ) {
    }

    public static function fromPage(int $page, int $perPage = 10): self
    {
        $offset = ($page - 1) * $perPage;
        return new self($perPage, $offset);
    }
}
