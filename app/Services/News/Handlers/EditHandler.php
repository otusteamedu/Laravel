<?php
declare(strict_types=1);

namespace App\Services\News\Handlers;

use App\Services\News\Exceptions\NewsNotFoundException;
use App\Services\News\Results\NewsDTO;
use App\Services\News\Results\Fetcher;
use App\Services\News\Repositories\NewsRepositoryInterface;

class EditHandler
{
    public function __construct(private NewsRepositoryInterface $newsRepository, private Fetcher $fetcher)
    {
    }


    /**
     * @param int $id
     *
     * @return NewsDTO
     * @throws NewsNotFoundException
     */
    public function __invoke(int $id): NewsDTO {

        $news = $this->newsRepository->find($id);

        if (!$news) {
            throw new NewsNotFoundException('News not found');
        }

        return $this->fetcher->fetch($news);
    }
}
