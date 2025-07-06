<?php

namespace App\Policies;

use App\Domain\News\Repositories\NewsRepositoryInterface;
use App\Models\News;
use App\Models\User;
use App\Domain\News\Entities\News as DomainNews;

class NewsPolicy
{

    public function __construct(private NewsRepositoryInterface $newsRepository)
    {
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, string|News $newsOrId): bool
    {
        $domainNews = $this->getDomainNews($newsOrId);

        return $this->isAdmin($user) || ($domainNews && ($user->id === $domainNews->getAuthor()->getId()));
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, string|News $newsOrId): bool
    {
        $domainNews = $this->getDomainNews($newsOrId);

        return $this->isAdmin($user) || ($domainNews && ($user->id === $domainNews->getAuthor()->getId()));
    }

    /**
     * @param User $user
     *
     * @return bool
     */
    private function isAdmin(User $user): bool {
        return $user->hasRole('admin');
    }

    /**
     * @param int|string|News $newsOrId
     *
     * @return DomainNews|null
     */
    private function getDomainNews(int|string|News $newsOrId): ?DomainNews {
        if (!($newsOrId instanceof News)) {
            return $this->newsRepository->find($newsOrId);
        } else {
            return $newsOrId;
        }
    }
}
