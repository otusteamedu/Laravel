<?php

namespace App\CacheDecorator\Area;

use App\Services\Area\AreaRepositoryInterface;
use Illuminate\Support\Facades\Cache;
use App\BusinessModels\Area as BusinessModelsArea;

class AreaRepositoryCacheDecorator implements AreaRepositoryInterface
{
    private AreaRepositoryInterface $repository;

    public function __construct(AreaRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return array<int, BusinessModelsArea>
     */
    public function getAll(): array
    {
        return Cache::remember('area.getAll', 3600, function () {
            return $this->repository->getAll();
        });
    }

    public function store(BusinessModelsArea $area): void
    {
        $this->repository->store($area);
        $this->refreshCache();
    }

    public function findById(int $id): BusinessModelsArea
    {
        return $this->repository->findById($id);
    }

    public function update(BusinessModelsArea $area): void
    {
        $this->repository->update($area);
        $this->refreshCache();
    }

    public function delete(int $id): void
    {
        $this->repository->delete($id);
        $this->refreshCache();
    }

    private function refreshCache(): void
    {
        $data = $this->repository->getAll();
        Cache::put('area.getAll', $data, 3600);
    }
}
