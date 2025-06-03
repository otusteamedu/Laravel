<?php

namespace App\Modules\ISS\src\Services\EducationExam\getExamQuestions;

use App\Modules\ISS\src\Services\EducationExam\EducationExamRepoInterface;
use App\Modules\ISS\src\Services\EducationExam\getExamQuestions\InputDTO;
use App\Modules\ISS\src\Services\EducationExam\getExamQuestions\OutputDTO;

class GetExamQuestions
{
    public EducationExamRepoInterface $repository;

    public function __construct(EducationExamRepoInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Достать все экзаменационные вопросы теста для данной точки обучающего маршрута (вместе с вериантами ответов, если они есть)
     * @param InputDTO $inputData
     * @return OutputDTO[]
     */
    public function getExamQuestions(InputDTO $inputData): array
    {
        try {
            $questions = $this->repository->getExamQuestions(['id' => $inputData->id]);

            for ($i=0; $i < count($questions); $i++) {
                $questions[$i]['answers'] = $this->repository->getExamAnswers(['questionId' => $questions[$i]['questionId']]);
            }
        } catch (\Error | \Exception $e) {
            $questions = [];
        }

        return array_map(
            function ($question) {

                $tmpAnswers = [];
                foreach ($question['answers'] as $answer) {
                    $tmpAnswers[] = new AnswerDTO(
                        id: $answer['id'],
                        answer: $answer['answer']
                    );
                }

                return new OutputDTO(
                    rerpId: $question['rerp_id'],
                    erpId: $question['erp_id'],
                    questionId: $question['questionId'],
                    questionName: $question['questionName'],
                    questionText: $question['questionText'],
                    answers: $tmpAnswers
                );
            },
            $questions
        );
    }
}
