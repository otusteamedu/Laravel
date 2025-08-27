<?php

namespace ISS\App\Application\Services\Exam\EducationQuestion\GetRefPointExamQuestions;

use ISS\App\Application\Services\Exam\EducationExamRepoInterface;
use ISS\App\Application\Services\Exam\EducationQuestion\GetRefPointExamQuestions\InputDTO;
use ISS\App\Application\Services\Exam\EducationQuestion\GetRefPointExamQuestions\AnswerDTO;
use ISS\App\Application\Services\Exam\EducationQuestion\GetRefPointExamQuestions\QuestionDTO;

class GetRefPointExamQuestions
{
    private EducationExamRepoInterface $repository;

    public function __construct(EducationExamRepoInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Достать все экзаменационные вопросы теста для данной СПРАВОЧНОЙ точки обучающего маршрута (вместе с вериантами ответов, если они есть)
     * @param InputDTO $inputData
     * @return array<QuestionDTO>
     */
    public function __invoke(InputDTO $inputData): array
    {
        try {
            $questions = $this->repository->getRefPointQuestions(['id'=> $inputData->id]);

        } catch (\Error | \Exception $e) {
            //запись в лог
            $questions = null;
        }

        if (!is_null($questions)) {
            return array_map(
                function ($question) {

                    $answersArray = [];
                    foreach ($question['exam_answer'] as $answer) {
                        $answersArray[] = new AnswerDTO(
                            id: $answer['id'],
                            answerName: $answer['short_answer_name'],
                            answerText: $answer['answer'],
                            questionId: $answer['question_id'],
                            isRight: $answer['is_right'],
                        );
                    }

                    return new QuestionDTO(
                        id: $question['id'],
                        questionName: $question['short_question_name'],
                        questionText: $question['question'],
                        answers: $answersArray,
                    );
                },
                $questions
            );
        } else {
            return [];
        }
    }
}
