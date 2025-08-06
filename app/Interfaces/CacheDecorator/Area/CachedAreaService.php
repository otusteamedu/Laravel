<?php

namespace App\Interfaces\CacheDecorator\Area;

use App\Application\Services\Area\AreaServiceInterface;
use App\Application\Services\Area\AreaDTO;
use Illuminate\Support\Facades\Cache;

class CachedAreaService implements AreaServiceInterface
{
    private AreaServiceInterface $service;

    public function __construct(AreaServiceInterface $service)
    {
        $this->service = $service;
    }

    /**
     * @return array<int, AreaDTO>
     */
    public function prepairDataForIndex(): array
    {
        return Cache::remember('area.getAll', 3600, function () {
            return $this->service->prepairDataForIndex();
        });
    }

    public function store(string $name): void
    {
        $this->service->store($name);
        $this->refreshCache();
    }

    public function prepairDataForEdit(int $id): AreaDTO
    {
        return $this->service->prepairDataForEdit($id);
    }

    public function update(int $id, string $name): void
    {
        $this->service->update($id, $name);
        $this->refreshCache();
    }

    public function delete(int $id): void
    {
        $this->service->delete($id);
        $this->refreshCache();
    }

    private function refreshCache(): void
    {
        $data = $this->service->prepairDataForIndex();
        Cache::put('area.getAll', $data, 3600);
    }
}
