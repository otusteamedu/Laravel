<?php

namespace App\Modules\ISS\src\Services\EducationExam\fillExamBlank;

use Illuminate\Support\Arr;
use App\Modules\ISS\src\Services\EducationExam\EducationExamRepoInterface;
use App\Modules\ISS\src\Services\EducationExam\fillExamBlank\InputDTO;
use App\Modules\ISS\src\Services\EducationExam\fillExamBlank\OutputDTO;
use App\Modules\ISS\src\Services\EducationExam\fillExamBlank\QuestionWithAnswersWithTextDTO;

class FillExamBlank
{
    private EducationExamRepoInterface $repository;

    public function __construct(EducationExamRepoInterface $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(InputDTO $inputDTO): OutputDTO
    {
        $examBlank = [];

        //достаем из базы тексты вопросов с id и тексты ответов с id
        try {
            $examQuestionTexts = $this->repository->getQuestionsText(Arr::pluck($inputDTO->checkedQuestions, 'questionId'));
            $examAnswersText = $this->repository->getAnswersText(
                array_merge(
                    Arr::pluck($inputDTO->checkedQuestions, 'answerId'),
                    Arr::pluck($inputDTO->checkedQuestions, 'rightAnswerId')
                )
            );
        } catch (\Error | \Exception $e) {
            //запись в лог
            $examQuestionTexts = [];
        }

        //проверяем что экзаменационные вопросы есть (а ответов может и не быть если все вопросы сложные)
        if (!empty($examQuestionTexts)) {
            $questionIndexes = array_column($examQuestionTexts, 'id');
            $answerIndexes = array_column($examAnswersText, 'id');

            foreach ($inputDTO->checkedQuestions as $question) {
                $indexOfQuestion = array_search($question['questionId'], $questionIndexes);
                if (is_numeric($question['answerId'])) {
                    $indexOfAnswer = array_search($question['answerId'], $answerIndexes);

                } else {
                    $indexOfAnswer = null;
                }
                if (is_numeric($question['rightAnswerId'])) {
                    $indexOfRightAnswer = array_search($question['rightAnswerId'], $answerIndexes);
                } else {
                    $indexOfRightAnswer = null;
                }

                $examBlank[] = new QuestionWithAnswersWithTextDTO(
                    questionId: $question['questionId'],
                    questionText: $examQuestionTexts[$indexOfQuestion]['question'],
                    answerId: $question['answerId'],
                    answerText: !is_null($indexOfAnswer) && ($indexOfAnswer !== false) ? $examAnswersText[$indexOfAnswer]['answer'] : null,
                    rightAnswerId: $question['rightAnswerId'],
                    rightAnswerText: !is_null($indexOfRightAnswer) && ($indexOfRightAnswer !==false ) ? $examAnswersText[$indexOfRightAnswer]['answer'] : null,
                );
            }
        }

        return new OutputDTO(examBlank: $examBlank);
    }
}
