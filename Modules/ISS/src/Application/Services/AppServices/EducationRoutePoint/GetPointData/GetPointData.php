<?php

namespace ISS\App\Application\Services\AppServices\EducationRoutePoint\GetPointData;

use ISS\App\Application\Services\Exam\EducationQuestion\GetRefPointExamQuestions\GetRefPointExamQuestions;
use ISS\App\Application\Services\Exam\EducationQuestion\GetRefPointExamQuestions\InputDTO as getExamQuestionInputDTO;
use ISS\App\Application\Services\EducationMaterial\GetAllMaterialsOfRefPoint\GetAllMaterialsOfRefPoint;
use ISS\App\Application\Services\EducationMaterial\GetAllMaterialsOfRefPoint\InputDTO as getMaterialInputDTO;
use ISS\App\Application\Services\AppServices\EducationRoutePoint\GetPointData\OutputDTO;

/**
 * @var GetRefPointExamQuestions $getRefPointExamQuestions сервис извлечения вопросов теста для справочной точки маршрута
 * @var GetAllMaterialsOfRefPoint $getAllMaterialsOfRefPoint сервис извденичения учебных матреиалов для справ.точки маршрута
 */

class GetPointData
{
    private GetRefPointExamQuestions $getRefPointExamQuestions;
    private GetAllMaterialsOfRefPoint $getAllMaterialsOfRefPoint;

    public function __construct(
        GetRefPointExamQuestions $getRefPointExamQuestions,
        GetAllMaterialsOfRefPoint $getAllMaterialsOfRefPoint
    )
    {
        $this->getRefPointExamQuestions = $getRefPointExamQuestions;
        $this->getAllMaterialsOfRefPoint = $getAllMaterialsOfRefPoint;
    }

    /**
     * Достать все данные справочной точки обучающего маршрута
     * @param InputDTO $inputData
     * @return null|OutputDTO
     */
    public function __invoke(InputDTO $inputData): ?OutputDTO
    {
        $examQuestionsWithAnswers = ($this->getRefPointExamQuestions)(new getExamQuestionInputDTO(id: $inputData->id));
        $educationMaterials = ($this->getAllMaterialsOfRefPoint)(new getMaterialInputDTO(pointId: $inputData->id));

        return new OutputDTO(
            questions: $examQuestionsWithAnswers,
            materials: $educationMaterials,
        );
    }
}
