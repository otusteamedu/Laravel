<?php

namespace ISS\App\Application\Services\EducationMaterial\GetMaterialsOfRefPointFilteredByType;

use ISS\App\Application\Services\EducationMaterial\EducationMaterialRepoInterface;
use ISS\App\Application\Services\EducationMaterial\GetMaterialsOfRefPointFilteredByType\InputDTO;
use ISS\App\Application\Services\EducationMaterial\GetMaterialsOfRefPointFilteredByType\OutputDTO;

class GetMaterialsOfRefPointFilteredByType
{
    private EducationMaterialRepoInterface $repository;

    public function __construct(EducationMaterialRepoInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Достать данные обучающих материалов для справочной точки обучающего маршрута
     * (фильтрованные по типу файла обучающего материала)
     * @param OutputDTO $inputData
     * @return OutputDTO[]
     */
    public function __invoke(InputDTO $inputData): array
    {
        try {
            $result = $this->repository->getMaterialsForRefPointFilteredByType(
                [
                    'point_id' => $inputData->pointId,
                    'type_name' => $inputData->type,
                    'returned_fields' => $inputData->returnedFields,
                ]
            );
        } catch (\Error | \Exception $e) {
            //запись в лог
            throw new \Exception("repo error getMaterialsForRefPointFilteredByType: {{$e->getMessage()}}!", 500);
        }

        return array_map(
            function ($item) {
                return new OutputDTO(
                    id: $item['id'] ?? null,
                    title: $item['title'] ?? null,
                    filePath: $item['file_path'] ?? null,
                    typeId: $item['material_type_id'] ?? null,
                );
            },
            $result
        );
    }
}
