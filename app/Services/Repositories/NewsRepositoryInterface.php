<?php

declare(strict_types=1);

namespace App\Services\Repositories;

use App\Models\News;

interface NewsRepositoryInterface
{
    /**
     * @return News[]
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
     * @return News|null
     */
    public function find(int $id): ?News;

    /**
     * @param News $news
     *
     * @return bool
     */
    public function save(News $news): bool;

    /**
     * @param News $news
     *
     * @return bool|null
     */
    public function delete(News $news): ?bool;

    /**
     * @param int $limit
     *
     * @return News[]
     */
    public function getLatest(int $limit): array;
}
