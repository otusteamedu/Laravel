<?php
declare(strict_types=1);

namespace App\Services\Category\Handlers;

use App\Services\Category\Commands\CommandDTO;
use App\Services\Category\Repositories\CategoryRepositoryInterface;
use Illuminate\Support\Facades\Cache;

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

        $res =  $this->categoryRepository->save($category);

        Cache::tags('categories')->flush(); // Очистить все кэши с тегом 'categories'

        return $res;
    }
}
