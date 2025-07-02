<?php

namespace App\Services\Commands\DeleteCategory;

use App\Services\Exceptions\Categories\CategoryNotFoundException;
use App\Services\Repositories\CategoryRepositoryInterface;

class Handler
{
    public function __construct(
        private CategoryRepositoryInterface $categoryRepository
    ) {
    }

    public function handle(Command $command): bool
    {
        $category = $this->categoryRepository->find($command->id);

        if (!$category) {
            throw new CategoryNotFoundException('Категория не найдена');
        }

        return $this->categoryRepository->delete($category);
    }
}
