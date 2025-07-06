<?php

namespace App\Application\UseCases\Category\Commands\UpdateCategory;

use App\Application\UseCases\Category\DTO\CategoryDTO;
use App\Domain\News\Exceptions\CategoryAlreadyExistsException;
use App\Domain\News\Exceptions\CategoryNotFoundException;
use App\Domain\News\Repositories\CategoryRepositoryInterface;

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

        if ($category->getName() !== $command->name &&
            $this->categoryRepository->existsByName($command->name)) {
            throw new CategoryAlreadyExistsException($command->name);
        }

        $category->update(
            name: $command->name,
            sort: $command->sort,
            isActive: $command->isActive,
            slug: $command->slug ?? null
        );

        $this->categoryRepository->save($category);

        return new CategoryDTO(
            id: $category->getId(),
            name: $category->getName(),
            slug: $category->getSlug(),
            isActive: $category->isActive(),
            sort: $category->getSort(),
        );
    }
}

