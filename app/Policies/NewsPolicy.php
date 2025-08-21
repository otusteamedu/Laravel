<?php

namespace App\Policies;

use App\Models\News;
use App\Models\User;
use App\Services\Repositories\NewsRepositoryInterface;
use Illuminate\Contracts\Auth\Authenticatable;

class NewsPolicy
{
    public function __construct(
        private NewsRepositoryInterface $newsRepository,
    ) {
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->getAuthIdentifier() === 1;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, News $news): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
//        return $user->getAuthIdentifier() === 2;
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(Authenticatable $user, int|string|News $newsOrId): bool
    {
        if (!($newsOrId instanceof News)) {
            $news = $this->newsRepository->find($newsOrId);
        } else {
            $news = $newsOrId;
        }

        return $news && ($user->id === $news->user_id);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, News $news): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, News $news): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, News $news): bool
    {
        return false;
    }
}
