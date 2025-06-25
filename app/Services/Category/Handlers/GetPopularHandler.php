<?php
declare(strict_types=1);

namespace App\Services\Category\Handlers;

use App\Services\Category\Results\Fetcher;
use App\Services\Category\Repositories\CategoryRepositoryInterface;
use Illuminate\Support\Facades\Cache;

class GetPopularHandler
{
    public function __construct(private CategoryRepositoryInterface $categoryRepository, private Fetcher $fetcher)
    {
    }

    public function __invoke() {

        return Cache::tags(['categories', 'news_count'])->remember('popular_categories_list', env('POPULAR_CATEGORIES_CACHE_TIME', 1800), function () {
            $categoriesCollection = $this->categoryRepository->getPopular();
            return $this->fetcher->fetch($categoriesCollection);
        });
    }
}
