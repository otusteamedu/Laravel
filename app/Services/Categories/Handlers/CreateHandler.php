<?php
namespace App\Services\Categories\Handlers;

use App\Repositories\Categories\CategoryRepositoryInterface;
use App\Services\Categories\Commands\CommandDTO;

class CreateHandler{
    public function __construct(private CategoryRepositoryInterface $categoryRepository)
    {
    }

    public function __invoke(CommandDTO $commandDTO): bool
    {
        $category = $this->categoryRepository->create();

        $category->name = $commandDTO->name;
        $category->color = $commandDTO->color;
        $category->description = $commandDTO->description;

        return $category->save();
    }
}
