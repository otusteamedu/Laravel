<?php

namespace ISS\App\Application\Services\Exam\EducationQuestion\GetQuestionsWithAnswers;

use ISS\App\Application\Services\Exam\EducationExamRepoInterface;
use ISS\App\Application\Services\Exam\EducationQuestion\GetQuestionsWithAnswers\InputDTO;
use ISS\App\Application\Services\Exam\EducationQuestion\GetQuestionsWithAnswers\OutputDTO;
use ISS\App\Application\Services\Exam\EducationQuestion\GetQuestionsWithAnswers\QuestionDTO;
use ISS\App\Application\Services\Exam\EducationQuestion\GetQuestionsWithAnswers\AnswerDTO;

class GetQuestionsWithAnswers
{
    private EducationExamRepoInterface $repository;

    public function __construct(EducationExamRepoInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Получить все вопросы с ответами, id которых есть в переданном массиве
     * @param InputDTO $inputData
     * @return OutputDTO
     * @throws \Exception
     */
    public function __invoke(InputDTO $inputData): OutputDTO
    {
        try {
            $result = $this->repository->getQuestionsWithAnswers($inputData->questionIds);
        } catch (\Error | \Exception $e) {
            //запись в лог
            throw new \Exception("repo error getQuestionsWithAnswers: {$e->getMessage()}");
        }

        return new OutputDTO(
            array_map(
                function ($tmp) {
                    $answersDTOs = array_map(
                        function ($answer) {
                            return new AnswerDTO(
                                id: $answer['id'],
                                answerName: $answer['short_answer_name'],
                                answerText: $answer['answer'],
                                questionId: $answer['question_id'],
                                isRight: $answer['is_right'],
                            );
                        },
                        $tmp['exam_answer']
                    );

                    return new QuestionDTO(
                        id: $tmp['id'],
                        questionName: $tmp['short_question_name'],
                        questionText: $tmp['question'],
                        refPointId: $tmp['point_id'],
                        answers: $answersDTOs
                    );
                },
                $result
            )
        );
    }
}
