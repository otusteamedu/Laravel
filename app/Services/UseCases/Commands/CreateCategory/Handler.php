<?php

namespace App\Services\UseCases\Commands\CreateCategory;

use App\Models\Category;
use App\Services\DTO\Categories\CategoryDTO;
use App\Services\Exceptions\Categories\CategoryAlreadyExistsException;
use App\Services\Exceptions\Categories\CategorySaveException;
use App\Services\Repositories\CategoryRepositoryInterface;

class Handler
{
    public function __construct(
        private CategoryRepositoryInterface $categoryRepository
    ) {
    }

    public function handle(Command $command): CategoryDTO
    {
        // Проверяем, существует ли уже категория с таким именем
        if ($this->categoryRepository->existsByName($command->name)) {
            throw new CategoryAlreadyExistsException($command->name);
        }

        $category = new Category();

        $category->name = $command->name;
        $category->sort = $command->sort;
        $category->is_active = $command->isActive;

        $result = $this->categoryRepository->save($category);

        if (!$result) {
            throw new CategorySaveException("Не удалось сохранить категорию '{$command->name}'");
        }

        return new CategoryDTO(
            id: $category->id,
            name: $category->name,
            slug: $category->slug,
            isActive: $category->is_active,
            sort: $category->sort,
        );
    }
}
