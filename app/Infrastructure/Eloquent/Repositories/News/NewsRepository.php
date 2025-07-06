<?php

declare(strict_types=1);

namespace App\Infrastructure\Eloquent\Repositories\News;

use App\Domain\News\Repositories\NewsRepositoryInterface;
use App\Models\News as EloquentNews;
use App\Domain\News\Entities\News as DomainNews;

class NewsRepository implements NewsRepositoryInterface
{
    /**
     * @return DomainNews[]
     */
    public function fetchAll(): array {
        $models = EloquentNews::with(['author', 'category'])->get();
        return array_map([NewsMapper::class, 'toEntity'], $models->all());
    }

    /**
     * @param int $limit
     * @param int $offset
     *
     * @return array
     */
    public function fetchPaginated(int $limit, int $offset): array {
        $models = EloquentNews::with(['author', 'category'])
                              ->orderBy('id', 'desc')
                              ->limit($limit)
                              ->offset($offset)
                              ->get();

        return array_map([NewsMapper::class, 'toEntity'], $models->all());
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return EloquentNews::count();
    }

    /**
     * @param int $id
     *
     * @return DomainNews|null
     */
    public function find(int $id): ?DomainNews {
        $model = EloquentNews::with(['author', 'category'])->find($id);
        return $model ? NewsMapper::toEntity($model) : null;
    }


    /**
     * @param DomainNews $news
     *
     * @return DomainNews
     */
    public function save(DomainNews $news): DomainNews {
        $model = NewsMapper::toModel($news);

        $model->save();

        return NewsMapper::toEntity($model);
    }

    /**
     * @param DomainNews $news
     *
     * @return bool|null
     */
    public function delete(DomainNews $news): ?bool {
        $model = EloquentNews::find($news->getId());
        return $model ? $model->delete() : null;
    }

    /**
     * @param int $limit
     *
     * @return DomainNews[]
     */
    public function getLatest(int $limit): array {
        $models = EloquentNews::with(['author', 'category'])
                              ->published()
                              ->orderBy('published_at', 'desc')
                              ->limit($limit)
                              ->get();

        return array_map([NewsMapper::class, 'toEntity'], $models->all());
    }
}
