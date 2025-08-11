<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\News;

interface NewsRepositoryInterface
{
    /**
     * @return News[]
     */
    public function fetchAll(): array;

    public function find(int $id): ?News;

    public function save(News $news): void;

    public function add(News $news): void;

    /**
     * @return News[]
     */
    public function fetchByAuthor(int $authorId): array;
}
