<?php

namespace App\Modules\ISS\src\Services\EducationMaterial\getEducationMaterials;

use App\Modules\ISS\src\Services\EducationMaterial\EducationMaterialRepoInterface;
use App\Modules\ISS\src\Services\EducationMaterial\getEducationMaterials\InputDTO;
use App\Modules\ISS\src\Services\EducationMaterial\getEducationMaterials\OutputDTO;

class GetMaterialsOfRefPoint
{
    private EducationMaterialRepoInterface $repository;

    public function __construct(EducationMaterialRepoInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Достать данные обучающих материалов для справочной точки обучающего маршрута
     * @param OutputDTO $inputData
     * @return OutputDTO[]
     */
    public function __invoke(InputDTO $inputData): array
    {
        try {
            $result = $this->repository->getEducationMaterialsForRefPoint(
                [
                    'point_id' => $inputData->pointId,
                    'returned_fields' => $inputData->returnedFields,
                ]
            );
        } catch (\Error | \Exception $e) {
            //запись в лог
            $result = [];
        }

        return array_map(
            function ($tmp) {
                return new OutputDTO(
                    id: $tmp['id'] ?? null,
                    tiitle: $tmp['title'] ?? null,
                    filePath: $tmp['file_path'] ?? null,
                    typeName: $tmp['educationMaterialType']['name'] ?? null,
                    typeId: $tmp['material_type_id'] ?? null,
                );
            },
            $result
        );
    }
}
