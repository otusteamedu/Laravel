<?php
declare(strict_types=1);

namespace App\Services\Category\Handlers;

use App\Services\Category\Exceptions\CategoryNotFoundException;
use App\Services\Category\Repositories\CategoryRepositoryInterface;
use Illuminate\Support\Facades\Cache;

class DestroyHandler
{
    public function __construct(private CategoryRepositoryInterface $categoryRepository)
    {
    }

    /**
     * @param int $categoryId
     *
     * @return bool|null
     * @throws CategoryNotFoundException
     */
    public function __invoke(int $categoryId): ?bool {
        $category = $this->categoryRepository->find($categoryId);

        if (!$category) {
            throw new CategoryNotFoundException('Category not found');
        }

        $res = $this->categoryRepository->delete($category);

        Cache::tags('categories')->flush(); // Очистить все кэши с тегом 'categories'

        return $res;
    }
}
