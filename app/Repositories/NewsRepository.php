<?php

namespace App\Repositories;

use App\Models\News;
use App\Services\NewsRepositoryInterface;

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

    public function save(News $news): void
    {
        $news->save();
    }

    public function add(News $news): void
    {
        $news->save();
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
