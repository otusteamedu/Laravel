<?php
declare(strict_types=1);

namespace App\Services\News\Handlers;

use App\Services\News\Commands\CommandDTO;
use App\Services\News\Exceptions\UserNotFoundException;
use App\Services\News\Results\Fetcher;
use App\Services\News\Results\NewsDTO;
use App\Services\News\Repositories\NewsRepositoryInterface;
use Illuminate\Support\Facades\Cache;

class UpdateHandler
{
    public function __construct(private NewsRepositoryInterface $newsRepository, private Fetcher $fetcher)
    {
    }

    /**
     * @param CommandDTO $commandDTO
     *
     * @return NewsDTO
     * @throws UserNotFoundException
     */
    public function __invoke(CommandDTO $commandDTO, bool $isAdmin = false): NewsDTO {
        $news = $this->newsRepository->find($commandDTO->id);

        if (!$news) {
            throw new UserNotFoundException('News not found');
        }

        $isChangedActivityCount = $news->is_draft !== $commandDTO->isDraft;

        $news->title = $commandDTO->title;
        $news->content = $commandDTO->content;
        $news->published_at = $commandDTO->publishedAt;
        $news->is_draft = $commandDTO->isDraft;
        //$news->category_id = $commandDTO->categoryId;
        $news->attachCategory($commandDTO->categoryId);

        if ($isAdmin) {
            $news->attachUser($commandDTO->userId);
            //$news->user_id = $commandDTO->userId;
        }

        $this->newsRepository->save($news);

        Cache::tags('news')->flush(); // Очистить все кэши с тегом 'news'

        if ($isChangedActivityCount) {
            Cache::tags('news_count')->flush();
        }

        return $this->fetcher->fetch($news);
    }
}
