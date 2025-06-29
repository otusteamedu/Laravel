<?php

declare(strict_types=1);

namespace App\Services\News\Repositories;

use App\Models\News;
use Illuminate\Pagination\LengthAwarePaginator;

interface NewsRepositoryInterface
{
    /**
     * @return News[]
     */
    public function fetchAll(): array;

    /**
     * @param string $column
     * @param        $direction
     *
     * @return LengthAwarePaginator
     */
    //public function fetchAllPaginate(string $column = 'id', $direction = 'asc', int $perPage = 10): LengthAwarePaginator;

    /**
     * @param int $id
     *
     * @return News|null
     */
    public function find(int $id): ?News;

    /**
     * @return News
     */
    public function create(): News;

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
    public function getLatest(int $limit = 10): array;
}
