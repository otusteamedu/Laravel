<?php

namespace App\Policies;

use App\Models\Category;
use App\Models\User;

class CategoryPolicy
{
    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $this->isAdmin($user);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, string|Category $categoryOrId): bool
    {
        return $this->isAdmin($user);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, string|Category $categoryOrId): bool
    {
        return $this->isAdmin($user);
    }

    /**
     * @param User $user
     *
     * @return bool
     */
    private function isAdmin(User $user): bool {
        return $user->hasRole('admin');
    }
}
