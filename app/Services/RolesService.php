<?php
namespace App\Services;

use App\Repositories\RolesRepository;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Role;

class RolesService
{
    public function __construct(
        private RolesRepository $repository,
    ) {}

    /**
     * @return Collection<array-key, Role>
     */
    public function getAll(): Collection
    {
        return $this->repository->fetchAll();
    }
}