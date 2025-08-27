<?php

namespace ISS\App\Application\Services\AppServices\Exam\GetExamQuestions;

use ISS\App\Application\Services\AppServices\Exam\GetExamQuestions\InputDTO;
use ISS\App\Application\Services\AppServices\Exam\GetExamQuestions\OutputDTO;
use ISS\App\Application\Services\RealEducationRoutePoint\GetRealRoutePointById\GetRealRoutePointById;
use ISS\App\Application\Services\RealEducationRoutePoint\GetRealRoutePointById\InputDTO as rerpInputDTO;
use ISS\App\Application\Services\Exam\EducationQuestion\GetRefPointExamQuestions\GetRefPointExamQuestions;
use ISS\App\Application\Services\Exam\EducationQuestion\GetRefPointExamQuestions\InputDTO as questionInputDTO;

class GetExamQuestions
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
     * Достать все экзаменационные вопросы теста для данной реальной точки обучающего маршрута
     * (вместе с вериантами ответов, если они есть)
     * @param InputDTO $inputData
     * @return OutputDTO[]
     */
    public function __invoke(InputDTO $inputData): array
    {
        try {
            //получить данные реальной точки маршрута и из них взять код справочной точки
            $refPointId = ($this->getRealRoutePointById)(new rerpInputDTO(id: $inputData->id))->routePointId;

            //получить все экзаменационные вопросы с ответами по коду справочной точки маршрута
            $questions = ($this->getRefPointExamQuestions)(new questionInputDTO(id: $refPointId));
        } catch (\Error | \Exception $e) {
            $questions = [];
            //запись в лог
        }

        return array_map(
            function ($question) use ($inputData, $refPointId) {

                $tmpAnswers = [];
                foreach ($question->answers as $answer) {
                    $tmpAnswers[] = new AnswerDTO(
                        id: $answer->id,
                        answer: $answer->answerText
                    );
                }

                return new OutputDTO(
                    rerpId: $inputData->id,
                    erpId: $refPointId,
                    questionId: $question->id,
                    questionName: $question->questionName,
                    questionText: $question->questionText,
                    answers: $tmpAnswers
                );
            },
            $questions
        );
    }
}
