<?php
namespace App\Services\Categories\Handlers;

use App\Services\Categories\Commands\CommandDTO;
use App\Services\Categories\Exceptions\CategoryNotFoundException;
use App\Services\Categories\Results\CategoryDTO;
use App\Repositories\Categories\CategoryRepositoryInterface;

class UpdateHandler
{
    public function __construct(private CategoryRepositoryInterface $categoryRepository)
    {
    }

    /**
     * @param CommandDTO $commandDTO
     *
     * @return CategoryDTO
     * @throws CategoryNotFoundException
     */
    public function __invoke(CommandDTO $commandDTO): CategoryDTO {
        $category = $this->categoryRepository->find($commandDTO->id);

        if (!$category) {
            throw new CategoryNotFoundException('Category not found');
        }

        $category->name = $commandDTO->name;
        $category->color = $commandDTO->color;
        $category->description = $commandDTO->description;

        $this->categoryRepository->save($category);

        return new CategoryDTO($category->id, $category->name, $category->color, $category->description);
    }
}
