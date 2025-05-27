<?php
declare(strict_types=1);

namespace App\Services\Category\Handlers;

use App\Services\Category\Exceptions\CategoryNotFoundException;
use App\Services\Category\Results\CategoryDTO;
use App\Services\Category\Results\Fetcher;
use App\Services\Category\Repositories\CategoryRepositoryInterface;

class ShowHandler
{
    public function __construct(private CategoryRepositoryInterface $categoryRepository)
    {
    }


    /**
     * @param int $id
     *
     * @return CategoryDTO
     * @throws CategoryNotFoundException
     */
    public function __invoke(int $id): CategoryDTO {

        $category = $this->categoryRepository->find($id);

        if (!$category) {
            throw new CategoryNotFoundException('Category not found');
        }

        return new CategoryDTO(
            $category->id,
            $category->name,
            $category->slug,
            $category->sort,
        );

    }
}
