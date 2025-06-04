<?php

namespace App\Services\Commands\DeleteCategory;

use App\Repositories\Categories\CategoryRepositoryInterface;
use App\Services\Exceptions\Categories\CategoryNotFoundException;

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