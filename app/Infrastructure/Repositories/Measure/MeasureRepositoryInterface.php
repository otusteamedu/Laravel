<?php

namespace App\Infrastructure\Repositories\Measure;

interface MeasureRepositoryInterface 
{
    /**
     * @return array <int, MeasureDTO>
     */
    public function getAll(): array;
}
