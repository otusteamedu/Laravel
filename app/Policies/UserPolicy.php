<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    const ADMIN_ROLE_ID = 1;

    protected function isAdmin(User $user): bool
    {
        return $user->role_id == self::ADMIN_ROLE_ID;
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $this->isAdmin($user);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user): bool
    {
        return $this->isAdmin($user);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user): bool
    {
        return $this->isAdmin($user);
    }
}
