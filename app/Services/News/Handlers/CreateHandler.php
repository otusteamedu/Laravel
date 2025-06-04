<?php
declare(strict_types=1);

namespace App\Services\News\Handlers;

use App\Services\News\Commands\CommandDTO;
use App\Services\News\Repositories\NewsRepositoryInterface;

class CreateHandler
{
    public function __construct(private NewsRepositoryInterface $newsRepository)
    {
    }

    /**
     * @param CommandDTO $commandDTO
     *
     * @return void
     */
    public function __invoke(CommandDTO $commandDTO, bool $isAdmin = false): bool {

        $news = $this->newsRepository->create();

        $news->title = $commandDTO->title;
        $news->content = $commandDTO->content;
        $news->published_at = $commandDTO->publishedAt;
        $news->is_draft = $commandDTO->isDraft;

        $news->attachCategory($commandDTO->categoryId);
        $news->attachUser($commandDTO->userId);

        //$news->category_id = $commandDTO->categoryId;
        //$news->user_id = $commandDTO->userId;

        return $this->newsRepository->save($news);
    }
}
