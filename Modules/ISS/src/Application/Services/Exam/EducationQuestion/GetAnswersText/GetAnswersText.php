<?php

namespace ISS\App\Application\Services\Exam\EducationQuestion\GetAnswersText;

use ISS\App\Infrastructure\Repositories\EducationExamRepo;
use ISS\App\Application\Services\Exam\EducationQuestion\GetAnswersText\InputDTO;
use ISS\App\Application\Services\Exam\EducationQuestion\GetAnswersText\OutputDTO;
use ISS\App\Application\Services\Exam\EducationQuestion\GetAnswersText\AnswerTextDTO;


class GetAnswersText
{
    private EducationExamRepo $repository;

    public function __construct(EducationExamRepo $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Получить тексты ОТВЕТОВ к экзаменационным вопросам для всех кодов ответов,
     * указанных во входном массиве
     * @param InputDTO $inputData
     * @return OutputDTO
     */
    public function __invoke(InputDTO $inputData): OutputDTO
    {
        try {
            $result = $this->repository->getAnswersText($inputData->answersIds);
        } catch (\Error | \Exception $e) {
            //запись в лог
            throw new \Exception("repo error getAnswersText {$e->getMessage()}");
        }

        return new OutputDTO(
            answerTexts: array_map(
                function ($tmp) {
                    return new AnswerTextDTO(
                        id: $tmp['id'],
                        answer: $tmp['answer']
                    );
                },
                $result
            )
        );
    }
}
