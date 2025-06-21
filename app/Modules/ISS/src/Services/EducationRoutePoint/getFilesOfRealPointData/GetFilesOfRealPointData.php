<?php

namespace App\Modules\ISS\src\Services\EducationRoutePoint\getFilesOfRealPointData;

use App\Modules\ISS\src\Repositories\EducationRoutePointRepo;
use App\Modules\ISS\src\Services\EducationRoutePoint\getFilesOfRealPointData\InputDTO;
use App\Modules\ISS\src\Services\EducationRoutePoint\getFilesOfRealPointData\OutputDTO;

class GetFilesOfRealPointData
{
    private EducationRoutePointRepo $repository;

    public function __construct(EducationRoutePointRepo $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Достать данные для видео/пдф/текстовых файлов точки обучающего маршрута
     * @param InputDTO $inputData
     * @return OutputDTO
     */
    public function __invoke(InputDTO $inputData): ?OutputDTO
    {
        $result = null;
        try {
            foreach (config('iss.ALLOWED_EDUCATION_MATERIAL_TYPES') as $matType) {

                $materials[$matType] = $this->repository->getFilesOfRealPointData(
                    [
                        'id' => $inputData->id,
                        'material_type' => $matType,
                    ]
                );
            }
            $result = new OutputDTO(materials: $materials);
        } catch (\Error | \Exception $e) {
            $result = null;
            //запись в лог
        }

        return $result;
    }
}
