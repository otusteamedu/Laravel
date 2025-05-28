<?php

namespace App\Repositories;

use App\Models\Role;

class RolesRepository
{
    public function fetchAll(): \Illuminate\Database\Eloquent\Collection
    {
        return Role::all();
    }
}