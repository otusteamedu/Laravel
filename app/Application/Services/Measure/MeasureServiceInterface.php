<?php

namespace App\Application\Services\Measure;

interface MeasureServiceInterface 
{
    /**
     * @return array <int, MeasureDTO>
     */
    public function prepairDataForIndex(): array;
}
