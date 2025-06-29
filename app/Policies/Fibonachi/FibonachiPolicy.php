<?php

namespace App\Policies\Fibonachi;

use App\Models\User;

class FibonachiPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function calculate(User $user, string $role = 'admin') {
        return $user->role === $role;
    }
}
