<?php

namespace App\Repositories;

use App\Models\Brand;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class BrandsRepository
{
    /**
     * @return Collection<array-key, Brand>
     */
    public function fetchAll(bool $warmup = false): Collection
    {
        $key = 'brands.all';
        $ttl = now()->addWeek();

        if ($warmup) {
            $brands = Brand::all();
            Cache::put($key, $brands, $ttl);
            return $brands;
        }

        $brands = Cache::remember($key, $ttl, function () {
            return Brand::all();
        });
        
        return $brands;
    }

    public function warmupCache(): void
    {
        $this->fetchAll(true);
    }
}