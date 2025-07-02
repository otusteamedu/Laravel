<?php

namespace App\Services\UseCases\Commands\UpdateCategory;

use App\Services\DTO\Categories\CategoryDTO;
use App\Services\Exceptions\Categories\CategoryAlreadyExistsException;
use App\Services\Exceptions\Categories\CategoryNotFoundException;
use App\Services\Repositories\CategoryRepositoryInterface;

class Handler
{
    public function __construct(
        private CategoryRepositoryInterface $categoryRepository
    ) {
    }

    public function handle(Command $command): CategoryDTO
    {
        $category = $this->categoryRepository->find($command->id);

        if (!$category) {
            throw new CategoryNotFoundException('Категория не найдена');
        }

        if ($category->name !== $command->name &&
            $this->categoryRepository->existsByName($command->name)) {
            throw new CategoryAlreadyExistsException($command->name);
        }

        $category->name = $command->name;
        $category->sort = $command->sort;
        $category->is_active = $command->isActive;

        $this->categoryRepository->save($category);

        return new CategoryDTO(
            id: $category->id,
            name: $category->name,
            slug: $category->slug,
            isActive: $category->is_active,
            sort: $category->sort,
        );
    }
}
