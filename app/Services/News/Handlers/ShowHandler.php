<?php
declare(strict_types=1);

namespace App\Services\News\Handlers;

use App\Services\News\Exceptions\UserNotFoundException;
use App\Services\News\Results\NewsDTO;
use App\Services\News\Results\Fetcher;
use App\Services\News\Repositories\NewsRepositoryInterface;

class ShowHandler
{
    public function __construct(private NewsRepositoryInterface $newsRepository, private Fetcher $fetcher)
    {
    }


    /**
     * @param int $id
     *
     * @return NewsDTO
     * @throws UserNotFoundException
     */
    public function __invoke(int $id): NewsDTO {

        $news = $this->newsRepository->find($id);

        if (!$news) {
            throw new UserNotFoundException('News not found');
        }

        return $this->fetcher->fetch($news);

    }
}
