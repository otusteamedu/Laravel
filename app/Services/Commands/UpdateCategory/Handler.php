<?php

namespace App\Services\Commands\UpdateCategory;

use App\Repositories\Categories\CategoryRepositoryInterface;
use App\Services\Exceptions\Categories\CategoryNotFoundException;
use App\Services\DTO\Categories\CategoryDTO;

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

        $category->name = $command->name;
        $category->color = $command->color;
        $category->description = $command->description;

        $this->categoryRepository->save($category);

        return new CategoryDTO(
            id: $category->id,
            name: $category->name,
            color: $category->color,
            description: $category->description,
            tasks_count: $category->tasks()->count(),
        );
    }
} 