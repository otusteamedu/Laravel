<?php

namespace App\Domain\Apartment\Repositories;

use App\Domain\Apartment\Apartment as ApartmentDomain;
use App\Domain\Apartment\ValueObjects\Owner;
use App\Domain\Apartment\ValueObjects\SerialNumber;
use App\Models\ApartmentModel; 

class ApartmentRepository
{
    /**
     * Возвращает массив доменных моделей Apartment с опциональным фильтром
     *
     * @param string|null $filter
     * @return ApartmentDomain[]
     */
    public function findAllWithFilter(?string $filter = null): array
    {
        $query = ApartmentModel::with(['details', 'fees']); // <-- ApartmentModel вместо Apartment

        if ($filter === 'balance_end_gt_6000') {
            $query->whereHas('fees', function ($q) {
                $q->where('balance_end', '>', 6000);
            });
        }

        $apartmentModels = $query->get();

        return $apartmentModels->map(function (ApartmentModel $model) { // <-- ApartmentModel вместо Apartment
            return new ApartmentDomain(
                new Owner($model->owner),
                new SerialNumber((int) $model->serial_number),
                $model->details->all(),
                $model->fees->all()
            );
        })->all();
    }

    /**
     * Сохраняет доменную модель Apartment в базу данных
     *
     * @param ApartmentDomain $apartment
     * @return ApartmentDomain
     */
    public function save(ApartmentDomain $apartment): ApartmentDomain
    {
        ApartmentModel::updateOrCreate( // <-- ApartmentModel вместо Apartment
            ['serial_number' => $apartment->getSerialNumber()->toInt()],
            ['owner' => $apartment->getOwner()->toString()]
        );

        return $apartment;
    }
}
