<?php

namespace App\Repositories;

use App\Models\Role;
use Illuminate\Database\Eloquent\Collection;

class RolesRepository
{
    /**
     * @return Collection<array-key, Role>
     */
    public function fetchAll(): Collection
    {
        return Role::all();
    }
}