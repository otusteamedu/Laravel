<?php

declare(strict_types=1);

namespace App\Application\UseCases\Category\DTO;

final readonly class PaginatedResult
{
    /**
     * @param CategoryDTO[] $items
     * @param int $total
     * @param int $limit
     * @param int $offset
     */
    public function __construct(
        public array $items,
        public int $total,
        public int $limit,
        public int $offset,
    ) {
    }

    public function getCurrentPage(): int
    {
        return intval(floor($this->offset / $this->limit)) + 1;
    }

    public function getPerPage(): int
    {
        return $this->limit;
    }

    public function hasMorePages(): bool
    {
        return $this->offset + $this->limit < $this->total;
    }
}
