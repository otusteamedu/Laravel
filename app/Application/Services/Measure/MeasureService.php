<?php

namespace App\Application\Services\Measure;

use App\Exceptions\NotFoundException;
use App\Repositories\Measure\MeasureRepositoryInterface;

final readonly class MeasureService implements MeasureServiceInterface
{
    public MeasureRepositoryInterface $measureRepository;

    public function __construct(MeasureRepositoryInterface $measureRepository)
    {
        $this->measureRepository = $measureRepository;
    }

    public function prepairDataForIndex(): array
    {
        $measures = $this->measureRepository->getAll();
        if (empty($Measures)) {
            throw new NotFoundException('Записи отсутствуют.');
        };
        return $Measures;
    }
}
