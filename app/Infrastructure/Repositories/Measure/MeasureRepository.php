<?php

namespace App\Infrastructure\Repositories\Measure;

use App\Infrastructure\EloquentModels\Measure;

class MeasureRepository implements MeasureRepositoryInterface
{
    public function getAll(): array
    {
        $measures = Measure::all();
        $measures = $measures->map(function($Measure) {
            return (new MeasureDTO($Measure));
        });
        return $measures->toArray();
    }
}
