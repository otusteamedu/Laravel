<?php

namespace ISS\App\Application\Services\AppServices\RealEducationRoutePoint\GetFilesOfRealPointData;

use ISS\App\Application\Services\EducationRoutePoint\EducationRoutePointRepoInterface;
use ISS\App\Application\Services\EducationMaterial\GetMaterialsOfRefPointFilteredByType\GetMaterialsOfRefPointFilteredByType;
use ISS\App\Application\Services\EducationMaterial\GetMaterialsOfRefPointFilteredByType\InputDTO as materialDTO;
use ISS\App\Application\Services\RealEducationRoutePoint\GetRealRoutePointById\GetRealRoutePointById;
use ISS\App\Application\Services\RealEducationRoutePoint\GetRealRoutePointById\InputDTO as realRoutePointDTO;

class GetFilesOfRealPointData
{
    //private EducationRoutePointRepoInterface $repository;
    private GetMaterialsOfRefPointFilteredByType $getMaterialsOfRefPointFilteredByType;
    private GetRealRoutePointById $getRealRoutePointById;

    public function __construct(
        //EducationRoutePointRepoInterface $repository,
        GetMaterialsOfRefPointFilteredByType $getMaterialsOfRefPointFilteredByType,
        GetRealRoutePointById $getRealRoutePointById,
    )
    {
        //$this->repository = $repository;
        $this->getMaterialsOfRefPointFilteredByType = $getMaterialsOfRefPointFilteredByType;
        $this->getRealRoutePointById = $getRealRoutePointById;
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

                //достать реальную точку маршрута (ее route_point_id)
                $erpId = ($this->getRealRoutePointById)(new realRoutePointDTO(
                    id: $inputData->id,
                    returnedFields: ['route_point_id'],
                ));

                //по route_point_id достать все материалы фильтрованные по $matType
                $erpMaterialsForType = ($this->getMaterialsOfRefPointFilteredByType)(new materialDTO(
                    pointId: $erpId->routePointId,
                    type: $matType,
                    returnedFields: ['title', 'file_path'],
                ));

                //закинуть массив материалов в $materials[$matType] если нет материалов то []
                $materials[$matType] = array_map(
                    function ($item) {
                        return ['title' => $item->title, 'file_path' => $item->filePath];
                    },
                    $erpMaterialsForType
                );
            }
            $result = new OutputDTO(materials: $materials);
        } catch (\Error | \Exception $e) {
            $result = null;
            //запись в лог
            throw new \Exception("error service GetFilesOfRealPointData: {$e->getMessage()}", 500);
        }

        return $result;
    }
}
