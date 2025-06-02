<?php
declare(strict_types=1);

namespace App\Services\Category\Handlers;

use App\Services\Category\Commands\CommandDTO;
use App\Services\Category\Exceptions\CategoryNotFoundException;
use App\Services\Category\Results\CategoryDTO;
use App\Services\Category\Repositories\CategoryRepositoryInterface;
use App\Services\Category\Results\Fetcher;

class UpdateHandler
{
    public function __construct(private CategoryRepositoryInterface $categoryRepository, private Fetcher $fetcher)
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
        $category->sort = $commandDTO->sort;

        $this->categoryRepository->save($category);

        return $this->fetcher->fetch($category);
    }
}
