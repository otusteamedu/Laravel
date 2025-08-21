<?php

declare(strict_types=1);

namespace App\Application\UseCases\News\DTO;

/**
 * DTO для пагинированного списка новостей
 */
final readonly class PaginatedResult
{
    /**
     * @param NewsDTO[] $items
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
