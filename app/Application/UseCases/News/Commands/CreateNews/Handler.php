<?php

namespace App\Application\UseCases\News\Commands\CreateNews;

use App\Application\Contracts\CacheInterface;
use App\Application\UseCases\News\DTO\NewsDTO;
use App\Domain\News\Entities\News as DomainNews;
use App\Domain\News\Exceptions\NewsSaveException;
use App\Domain\News\Repositories\NewsRepositoryInterface;
use App\Domain\User\Repositories\UserRepositoryInterface;
use App\Application\UseCases\News\DTO\AuthorDTO;

class Handler
{
    public function __construct(
        private NewsRepositoryInterface $newsRepository,
        private UserRepositoryInterface $userRepository,
        private CacheInterface $cache
    ) {
    }

    public function handle(Command $command): NewsDTO
    {
        $user = $command->user_id
            ? $this->userRepository->find($command->user_id)
            : null;
        $news = new DomainNews(
            id: null, // или генерируйте UUID, если нужно
            name: $command->name,
            user:$user,
            text: $command->text,
            photo: $command->photo,
            preview:$command->preview,
            link:$command->link,
            createAt: $command->createAt?->format('c'),
        );

        try {
            $domainNews = $this->newsRepository->save($news);
        } catch (\Exception) {
            throw new NewsSaveException("Не удалось сохранить новость '{$command->name}'");
        }

        $this->cache->flushTagged('news');
        $this->cache->flushTagged('news_count');

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
