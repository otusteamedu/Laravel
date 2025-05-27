<?php
declare(strict_types=1);

namespace App\Services\Category\Handlers;

use App\Services\Category\Commands\CommandDTO;
use App\Services\Category\Repositories\CategoryRepositoryInterface;

class CreateHandler
{
    public function __construct(private CategoryRepositoryInterface $categoryRepository)
    {
    }

    /**
     * @param CommandDTO $categoryDTO
     *
     * @return void
     */
    public function __invoke(CommandDTO $commandDTO): bool {

        $category = $this->categoryRepository->create();

        $category->name = $commandDTO->name;
        $category->sort = $commandDTO->sort;

        return $this->categoryRepository->save($category);
    }
}
