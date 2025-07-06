<?php

namespace App\Application\UseCases\Category\Commands\DeleteCategory;

use App\Domain\News\Exceptions\CategoryNotFoundException;
use App\Domain\News\Repositories\CategoryRepositoryInterface;

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

