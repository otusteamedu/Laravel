<?php

declare(strict_types=1);

namespace App\Infrastructure\Eloquent\Repositories\News;

use App\Models\News;
use App\Services\Repositories\NewsRepositoryInterface;


class NewsRepository implements NewsRepositoryInterface
{
    /**
     * @return News[]
     */
    public function fetchAll(): array {
        return News::all()->all();
    }

    /**
     * @param int $limit
     * @param int $offset
     *
     * @return array
     */
    public function fetchPaginated(int $limit, int $offset): array {
        return News::query()
                       ->orderBy('id', 'desc')
                       ->limit($limit)
                       ->offset($offset)
                       ->get()
                       ->all();
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return News::count();
    }

    /**
     * @param int $id
     *
     * @return News|null
     */
    public function find(int $id): ?News {
        return News::query()->find($id);
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
    public function getLatest(int $limit): array {
        return News::query()->published()
           ->orderBy('published_at', 'desc')
           ->limit($limit)
           ->get()
           ->all();
    }
}
