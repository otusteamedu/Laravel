<?php
namespace App\Services\Categories\Handlers;

use App\Services\Categories\Exceptions\CategoryNotFoundException;
use App\Repositories\Categories\CategoryRepositoryInterface;

class DestroyHandler{

    public function __construct(private CategoryRepositoryInterface $categoryRepository)
    {
    }

    public function __invoke(int $id): ?bool
    {
        $category = $this->categoryRepository->find($id);

        if (!$category) {
            throw new CategoryNotFoundException('Категория не найдена');
        }

        return $this->categoryRepository->delete($category);
    }
}
