<?php

declare(strict_types=1);

namespace App\Repositories\News;

use App\Models\News;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Services\News\Repositories\NewsRepositoryInterface;

class NewsRepository implements NewsRepositoryInterface
{
    /**
     * @return News[]
     */
    public function fetchAll(): array {
        return News::all()->all();
    }

    /**
     * @param string $column
     * @param        $direction
     * @param int    $perPage
     *
     * @return LengthAwarePaginator
     */
    /*public function fetchAllPaginate(string $column = 'id', $direction = 'asc', int $perPage = 10): LengthAwarePaginator {
        return News::query()->orderBy($column, $direction)->paginate($perPage);
    }*/

    /**
     * @param int $id
     *
     * @return News|null
     */
    public function find(int $id): ?News {
        return News::query()->find($id);
    }

    /**
     * @return News
     */
    public function create(): News {
        return new News;
    }

    /**
     * @param News $news
     *
     * @return bool
     */
    public function save(News $news): bool {
        return $news->save();
    }

    /**
     * @param News $news
     *
     * @return bool|null
     */
    public function delete(News $news): ?bool {
        return $news->delete();
    }

    /**
     * @param int $limit
     *
     * @return News[]
     */
    public function getLatest(int $limit = 10): array {
        return News::query()->where('is_draft', false)
           ->orderBy('published_at', 'desc')
           ->limit($limit)
           ->get()
           ->all();
    }
}
