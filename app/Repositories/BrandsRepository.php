<?php

namespace App\Repositories;

use App\Models\Brand;
use Illuminate\Database\Eloquent\Collection;

class BrandsRepository
{
    /**
     * @return Collection<array-key, Brand>
     */
    public function fetchAll(): Collection
    {
        return Brand::all();
    }
}