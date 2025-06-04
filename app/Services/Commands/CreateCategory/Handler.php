<?php

namespace App\Services\Commands\CreateCategory;

use App\Models\Category;
use App\Repositories\Categories\CategoryRepositoryInterface;

class Handler
{
    public function __construct(
        private CategoryRepositoryInterface $categoryRepository
    ) {
    }

    public function handle(Command $command): bool
    {
        $category = new Category();

        $category->name = $command->name;
        $category->color = $command->color;
        $category->description = $command->description;

        return $this->categoryRepository->save($category);
    }
} 