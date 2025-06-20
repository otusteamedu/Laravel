<?php

namespace App\Services\Commands\CreateCategory;

use App\Models\Category;
use App\Repositories\Categories\CategoryRepositoryInterface;
use App\Services\Exceptions\Categories\CategoryAlreadyExistsException;
use App\Services\Exceptions\Categories\CategorySaveException;

class Handler
{
    public function __construct(
        private CategoryRepositoryInterface $categoryRepository
    ) {
    }

    public function handle(Command $command): bool
    {
        // Проверяем, существует ли уже категория с таким именем
        if ($this->categoryRepository->existsByName($command->name)) {
            throw new CategoryAlreadyExistsException($command->name);
        }

        $category = new Category();

        $category->name = $command->name;
        $category->color = $command->color;
        $category->description = $command->description;

        $result = $this->categoryRepository->save($category);
        
        if (!$result) {
            throw new CategorySaveException("Не удалось сохранить категорию '{$command->name}'");
        }

        return $result;
    }
} 