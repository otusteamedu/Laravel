<?php

declare(strict_types=1);

namespace App\Repositories\News;

use App\Models\News;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Services\News\Repositories\NewsRepositoryInterface;

class NewsRepository implements NewsRepositoryInterface
{
    /**
     * @param News $news
     */
    public function __construct(private News $news)
    {
    }

    /**
     * @return array
     */
    public function fetchAll(): array {
        return $this->news::all()->all();
    }

    /**
     * @param string $column
     * @param        $direction
     * @param int    $perPage
     *
     * @return LengthAwarePaginator
     */
    /*public function fetchAllPaginate(string $column = 'id', $direction = 'asc', int $perPage = 10): LengthAwarePaginator {
        return $this->news->query()->orderBy($column, $direction)->paginate($perPage);
    }*/

    /**
     * @param int $id
     *
     * @return News|null
     */
    public function find(int $id): ?News {
        return $this->news::query()->find($id);
    }

    /**
     * @return News
     */
    public function create(): News {
        return $this->news;
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
}
