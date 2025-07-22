<?php
namespace App\Services;

use App\Repositories\BrandsRepository;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Brand;

class BrandsService
{
    public function __construct(
        private BrandsRepository $repository,
    ) {}

    /**
     * @return Collection<array-key, Brand>
     */
    public function getAll(): Collection
    {
        return $this->repository->fetchAll();
    }
}