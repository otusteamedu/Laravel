<?php

namespace ISS\App\Application\Services\Exam\EducationQuestion\GetQuestionsText;

use ISS\App\Infrastructure\Repositories\EducationExamRepo;
use ISS\App\Application\Services\Exam\EducationQuestion\GetQuestionsText\InputDTO;
use ISS\App\Application\Services\Exam\EducationQuestion\GetQuestionsText\OutputDTO;
use ISS\App\Application\Services\Exam\EducationQuestion\GetQuestionsText\QuestionTextDTO;

class GetQuestionsText
{
    private EducationExamRepo $repository;

    public function __construct(EducationExamRepo $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Получить тексты экзаменационных вопросов для всех кодов вопросов,
     * указанных во входном массиве
     * @param InputDTO $inputData
     * @return OutputDTO
     */
    public function __invoke(InputDTO $inputData): OutputDTO
    {
        try {
            $result = $this->repository->getQuestionsText($inputData->questionIds);
        } catch (\Error | \Exception $e) {
            //запись в лог
            throw new \Exception("repo error getQuestionsText {$e->getMessage()}");
        }

        return new OutputDTO(
            questionTexts: array_map(
                function ($tmp) {
                    return new QuestionTextDTO(
                        id: $tmp['id'],
                        question: $tmp['question']
                    );
                },
                $result
            )
        );
    }
}
