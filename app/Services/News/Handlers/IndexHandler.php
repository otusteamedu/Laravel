<?php
declare(strict_types=1);

namespace App\Services\News\Handlers;

use App\Services\News\Results\Fetcher;
use App\Services\News\Repositories\NewsRepositoryInterface;

class IndexHandler
{
    public function __construct(private NewsRepositoryInterface $newsRepository, private Fetcher $fetcher)
    {
    }


    public function __invoke() {

        $newsCollection = $this->newsRepository->fetchAll();

        return $this->fetcher->fetch($newsCollection);

    }
}
