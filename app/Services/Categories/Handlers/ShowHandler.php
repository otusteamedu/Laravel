<?php
namespace App\Services\Categories\Handlers;

use App\Services\Categories\Exceptions\CategoryNotFoundException;
use App\Services\Categories\Results\CategoryDTO;
use App\Repositories\Categories\CategoryRepositoryInterface;

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
            throw new CategoryNotFoundException('Категория не найдена');
        }

        return new CategoryDTO(
            $category->id,
            $category->name,
            $category->color,
            $category->description
        );

    }
}
