<?php
declare(strict_types=1);

namespace App\Services\Category\Handlers;

use App\Services\Category\Exceptions\CategoryNotFoundException;
use App\Services\Category\Repositories\CategoryRepositoryInterface;

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

        return $this->categoryRepository->delete($category);
    }
}
