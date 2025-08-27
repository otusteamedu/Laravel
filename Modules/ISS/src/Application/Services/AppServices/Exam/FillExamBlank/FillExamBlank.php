<?php

namespace ISS\App\Application\Services\AppServices\Exam\FillExamBlank;

use Illuminate\Support\Arr;
use ISS\App\Application\Services\AppServices\Exam\FillExamBlank\InputDTO;
use ISS\App\Application\Services\AppServices\Exam\FillExamBlank\OutputDTO;
use ISS\App\Application\Services\AppServices\Exam\FillExamBlank\QuestionWithAnswersWithTextDTO;
use ISS\App\Application\Services\Exam\EducationQuestion\GetAnswersText\GetAnswersText;
use ISS\App\Application\Services\Exam\EducationQuestion\GetAnswersText\InputDTO as answerTextInputDTO;
use ISS\App\Application\Services\Exam\EducationQuestion\GetQuestionsText\GetQuestionsText;
use ISS\App\Application\Services\Exam\EducationQuestion\GetQuestionsText\InputDTO as questionTextInputDTO;

use ISS\App\Domain\Exam\ExamBlank;

class FillExamBlank
{
    private GetAnswersText $getAnswersText;
    private GetQuestionsText $getQuestionsText;

    public function __construct(
        GetAnswersText $getAnswersText,
        GetQuestionsText $getQuestionsText
    )
    {
        $this->getAnswersText = $getAnswersText;
        $this->getQuestionsText = $getQuestionsText;
    }

    public function __invoke(InputDTO $inputDTO): OutputDTO
    {
        $examBlank = [];

        //достаем из базы тексты вопросов с id и тексты ответов с id
        try {
            $examQuestionTexts = ($this->getQuestionsText)(new questionTextInputDTO(
                Arr::pluck($inputDTO->checkedQuestions, 'questionId')
            ))->questionTexts;
            $examAnswersText = ($this->getAnswersText)(new answerTextInputDTO(
                array_merge(
                    Arr::pluck($inputDTO->checkedQuestions, 'answerId'),
                    Arr::pluck($inputDTO->checkedQuestions, 'rightAnswerId')
                )
            ))->answerTexts;
        } catch (\Error | \Exception $e) {
            //запись в лог
            $examQuestionTexts = [];
        }

        //создаем объект домена для бланка экзамена
        $examBlankDomain = new ExamBlank(
            $inputDTO->checkedQuestions,
            array_map(
                function ($qText) {
                    return ['id' => $qText->id, 'question' => $qText->question];
                },
                $examQuestionTexts
            ),
            array_map(
                function ($aText) {
                    return ['id' => $aText->id, 'answer' => $aText->answer];
                },
                $examAnswersText
            )
        );

        //формируем и получаем заполненный бланк экзамена
        $examBlankDomain->makeExamFilledBlank();
        $result = $examBlankDomain->getExamBlank();

        //переводим формат бланка из формата домена в формат выходного дто сервиса
        return new OutputDTO(examBlank: array_map(
            function ($tmp) {
                return new QuestionWithAnswersWithTextDTO(
                    questionId: $tmp->questionId,
                    questionText: $tmp->questionText,
                    answerId: $tmp->answerId,
                    answerText: $tmp->answerText,
                    rightAnswerId: $tmp->rightAnswerId,
                    rightAnswerText: $tmp->rightAnswerText
                );
            },
            $result
        ));
    }
}
