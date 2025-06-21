<?php

namespace App\Repositories;

use App\Models\Role;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class RolesRepository
{
    /**
     * @return Collection<array-key, Role>
     */
    public function fetchAll(bool $warmup = false): Collection
    {
        $key = 'roles.all';
        $ttl = now()->addWeek();

        $roles = Cache::remember($key, $ttl, function () {
            return Role::all();
        });

        return $roles;
    }

    public function warmupCache(): void
    {
        $this->fetchAll(true);
    }
}