<?php

namespace App\Infrastructure\Eloquent\Repositories;

use App\Models\News;
use App\Services\Repositories\NewsRepositoryInterface;

class NewsRepository implements NewsRepositoryInterface
{
    public function fetchAll(): array
    {
        return News::all()->all();
    }

    public function find(int $id): ?News
    {
        return News::query()->find($id);
    }

    public function save(News $News): void
    {
        $News->save();
    }

    public function add(News $News): void
    {
        $News->save();
    }

    /**
     * @return News[]
     */
    public function fetchByAuthor(int $authorId): array
    {
        return News::query()
            ->where('user_id', $authorId)
            ->get()
            ->all()
        ;
    }
}
