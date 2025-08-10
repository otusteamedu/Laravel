<?php

namespace App\Policies;

use App\Models\Role;
use Illuminate\Contracts\Auth\Authenticatable;

class RolesPolicy
{
    /**
     * Константы для удобства использования по коду.
     * Должны иметь значения, соответствующие именам методов.
     * Так как гейт будет дергать их методы
     * См AppServiceProvider::boot()
     */
    public const ADMIN = 'admin';
    public const EDITOR = 'editor';
    public const USER = 'user';

    public function admin(Authenticatable $user): bool
    {
        return $user->getRole() === Role::ADMIN;
    }

    public function editor(Authenticatable $user): bool
    {
        return $user->getRole() === Role::EDITOR;
    }

    public function user(Authenticatable $user): bool
    {
        return $user->getRole() === Role::USER;
    }
}
