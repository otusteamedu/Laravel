<?php

namespace ISS\App\Application\Services\EducationMaterial\GetAllMaterialsOfRefPoint;

use ISS\App\Application\Services\EducationMaterial\EducationMaterialRepoInterface;
use ISS\App\Application\Services\EducationMaterial\GetAllMaterialsOfRefPoint\InputDTO;
use ISS\App\Application\Services\EducationMaterial\GetAllMaterialsOfRefPoint\OutputDTO;

class GetAllMaterialsOfRefPoint
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
            $result = $this->repository->getAllEducationMaterialsForRefPoint(
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
                    title: $tmp['title'] ?? null,
                    filePath: $tmp['file_path'] ?? null,
                    typeName: $tmp['educationMaterialType']['name'] ?? null,
                    typeId: $tmp['material_type_id'] ?? null,
                );
            },
            $result
        );
    }
}
