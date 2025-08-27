<?php

namespace ISS\App\Application\Services\AppServices\Exam\IsExamComplicated;

use ISS\App\Application\Services\AppServices\Exam\IsExamComplicated\InputDTO;
use ISS\App\Application\Services\AppServices\Exam\IsExamComplicated\OutputDTO;

use ISS\App\Application\Services\RealEducationRoutePoint\GetRealRoutePointById\GetRealRoutePointById;
use ISS\App\Application\Services\RealEducationRoutePoint\GetRealRoutePointById\InputDTO as rerpInputDTO;
use ISS\App\Application\Services\Exam\EducationQuestion\GetRefPointExamQuestions\GetRefPointExamQuestions;
use ISS\App\Application\Services\Exam\EducationQuestion\GetRefPointExamQuestions\InputDTO as examQuestionInputDTO;

use ISS\App\Domain\Exam\Exam;

class IsExamComplicated
{
    private GetRealRoutePointById $getRealRoutePointById;
    private GetRefPointExamQuestions $getRefPointExamQuestions;

    public function __construct(
        GetRealRoutePointById $getRealRoutePointById,
        GetRefPointExamQuestions $getRefPointExamQuestions
    )
    {
        $this->getRealRoutePointById = $getRealRoutePointById;
        $this->getRefPointExamQuestions = $getRefPointExamQuestions;
    }

    /**
     * Определение типа теста (простой или сложный)
     * @param InputDTO $inputData
     * @return OutputDTO
     */
    public function __invoke(InputDTO $inputData): OutputDTO
    {
        //получаем код справочной точки маршрута по коду реальной точки маршрута
        $refPointId = ( $this->getRealRoutePointById)( new rerpInputDTO(id: $inputData->id))->routePointId;

        //используя $refPointId, получаем ее экзаменационные вопросы с ответами
        $questionsWithAnswers = ($this->getRefPointExamQuestions)(new examQuestionInputDTO(id: $refPointId));

        //считаем количество вопросов, не содержащих ответов
        $questionsWithoutAnyAnswerCount = 0;
        foreach ($questionsWithAnswers as $q) {
            if (count($q->answers) === 0) {
                $questionsWithoutAnyAnswerCount++;
            }
        }

        //используя бизнес правило, определяем сложный экзамен или нет и возвращаем результат
        return new OutputDTO(isComplicated: Exam::isExamComplicated($questionsWithoutAnyAnswerCount));
    }
}
