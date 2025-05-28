<?php
namespace App\Services;

use App\Repositories\RolesRepository;

class RolesService
{
    public function __construct(
        private RolesRepository $repository,
    ) {}

    public function getAll(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->repository->fetchAll();
    }
}