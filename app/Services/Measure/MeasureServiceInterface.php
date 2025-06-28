<?php

namespace App\Services\Measure;

interface MeasureServiceInterface 
{
    /**
     * @return array <int, MeasureDTO>
     */
    public function prepairDataForIndex(): array;
}
