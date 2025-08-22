<?php

namespace App\Application\Services\Measure;

use App\Application\Exceptions\NotFoundServiceException;
use App\Infrastructure\Repositories\Measure\MeasureRepositoryInterface;

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
        if (empty($measures)) {
            throw new NotFoundServiceException('Записи отсутствуют.');
        };
        return $measures;
    }
}
