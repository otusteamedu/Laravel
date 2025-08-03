<?php

namespace App\Modules\ISS\src\Services\EducationRoutePoint\getPointData;

use App\Modules\ISS\src\Services\EducationExam\getRefPointExamQuestions\GetRefPointExamQuestions;
use App\Modules\ISS\src\Services\EducationExam\getRefPointExamQuestions\InputDTO as getExamQuestionInputDTO;
use App\Modules\ISS\src\Services\EducationMaterial\getEducationMaterials\GetMaterialsOfRefPoint;
use App\Modules\ISS\src\Services\EducationMaterial\getEducationMaterials\InputDTO as getMaterialInputDTO;
use App\Modules\ISS\src\Services\EducationRoutePoint\getPointData\OutputDTO;

/**
 * @var GetRefPointExamQuestions $getRefPointExamQuestions сервис извлечения вопросов теста для справочной точки маршрута
 * @var GetMaterialsOfRefPoint $getMaterialsOrRefPoint сервис извденичения учебных матреиалов для справ.точки маршрута
 */

class GetPointData
{
    private GetRefPointExamQuestions $getRefPointExamQuestions;
    private GetMaterialsOfRefPoint $getMaterialsOfRefPoint;

    public function __construct(
        GetRefPointExamQuestions $getRefPointExamQuestions,
        GetMaterialsOfRefPoint $getMaterialsOfRefPoint
    )
    {
        $this->getRefPointExamQuestions = $getRefPointExamQuestions;
        $this->getMaterialsOfRefPoint = $getMaterialsOfRefPoint;
    }

    /**
     * Достать все данные справочной точки обучающего маршрута
     * @param InputDTO $inputData
     * @return null|OutputDTO
     */
    public function __invoke(InputDTO $inputData): ?OutputDTO
    {
        $examQuestionsWithAnswers = ($this->getRefPointExamQuestions)(new getExamQuestionInputDTO(id: $inputData->id));
        $educationMaterials = ($this->getMaterialsOfRefPoint)(new getMaterialInputDTO(pointId: $inputData->id));

        return new OutputDTO(
            questions: $examQuestionsWithAnswers,
            materials: $educationMaterials,
        );
    }
}
