<?php
declare(strict_types=1);

namespace App\Services\Category\Handlers;

use App\Services\Category\Results\Fetcher;
use App\Services\Category\Repositories\CategoryRepositoryInterface;

class IndexHandler
{
    public function __construct(private CategoryRepositoryInterface $categoryRepository, private Fetcher $fetcher)
    {
    }


    public function __invoke() {


        $categoriesCollection = $this->categoryRepository->fetchAll();

        return $this->fetcher->fetch($categoriesCollection);

    }
}
