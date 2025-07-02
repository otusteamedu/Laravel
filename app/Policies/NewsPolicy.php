<?php

namespace App\Policies;

use App\Models\News;
use App\Models\User;
use App\Services\Repositories\NewsRepositoryInterface;


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
        $news = $this->getNewsFromParam($newsOrId);

        return $this->isAdmin($user) || ($news && ($user->id === $news->user_id));
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, string|News $newsOrId): bool
    {
        $news = $this->getNewsFromParam($newsOrId);

        return $this->isAdmin($user) || ($news && ($user->id === $news->user_id));
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
     * @return News|null
     */
    private function getNewsFromParam(int|string|News $newsOrId): ?News {
        if (!($newsOrId instanceof News)) {
            return $this->newsRepository->find($newsOrId);
        } else {
            return $newsOrId;
        }
    }
}
