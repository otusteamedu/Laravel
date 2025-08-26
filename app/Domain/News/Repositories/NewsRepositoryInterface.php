<?php

declare(strict_types=1);

namespace App\Domain\News\Repositories;

use App\Domain\News\Entities\News as DomainNews;

interface NewsRepositoryInterface
{
    /**
     * @return DomainNews[]
     */
    public function fetchAll(): array;

    /**
     * @param int $limit
     * @param int $offset
     *
     * @return array
     */
    public function fetchPaginated(int $limit, int $offset): array;

    /**
     * @return int
     */
    public function count(): int;

    /**
     * @param int $id
     *
     * @return DomainNews|null
     */
    public function find(int $id): ?DomainNews;

    /**
     * @param DomainNews $news
     *
     * @return DomainNews
     */
    public function save(DomainNews $news): DomainNews;

    /**
     * @param DomainNews $news
     *
     * @return bool|null
     */
    public function delete(DomainNews $news): ?bool;

    /**
     * @param int $limit
     *
     * @return DomainNews[]
     */
    public function getLatest(int $limit): array;
}
