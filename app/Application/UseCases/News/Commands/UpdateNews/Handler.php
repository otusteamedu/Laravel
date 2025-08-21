<?php

namespace App\Application\UseCases\News\Commands\UpdateNews;

use App\Application\Contracts\CacheInterface;
use App\Application\UseCases\News\DTO\NewsDTO;
use App\Domain\News\Exceptions\NewsNotFoundException;
use App\Domain\News\Exceptions\NewsSaveException;
use App\Domain\News\Repositories\NewsRepositoryInterface;
use App\Domain\User\Repositories\UserRepositoryInterface;
use App\Application\UseCases\News\DTO\AuthorDTO;

class Handler
{
    public function __construct(
        private NewsRepositoryInterface $newsRepository,
        private UserRepositoryInterface $userRepository,
        private CacheInterface $cache,
    ) {
    }

    /**
     * @param Command $command
     * @param bool    $isAdmin
     *
     * @return NewsDTO
     * @throws NewsNotFoundException
     */
    public function handle(Command $command, bool $isAdmin = false): NewsDTO
    {
        $news = $this->newsRepository->find($command->id);

        if (!$news) {
            throw new NewsNotFoundException('Новость не найдена');
        }


        $author = $command->user_id
            ? $this->userRepository->find($command->user_id)
            : null;


        $news->update(
            name: $command->name,
            text: $command->text,
            link: $command->link ?? 'test'
        );

        $createAt = $command->createAt ?: null;
        $news->publish($createAt);
        

        if ($isAdmin && $command->user_id) {
            $user = $this->userRepository->find($command->user_id);
            if ($user) {
                $news->setUser($user);
            }
        }

        try {
            $domainNews = $this->newsRepository->save($news);
        } catch (\Exception) {
            throw new NewsSaveException("Не удалось сохранить новость '{$command->name}'");
        }

        $this->cache->flushTagged('news');

        return new NewsDTO(
            id: $domainNews->getId(),
            name: $domainNews->getName(),
            text: $domainNews->getText(),
            link: $domainNews->getLink(),
            photo: $domainNews->getPhoto(),
            preview: $domainNews->getPreview(),
            createdAt: $domainNews->getCreatedAt(),
            updatedAt: $domainNews->getUpdatedAt(),
            createAt: $domainNews->getCreateAt(),
            user: $user ? new AuthorDTO(
                    id: $user->getId(),
                    name: $user->getName(),
                    email: $user->getEmail(),
                ) : null,
        );
    }
}
