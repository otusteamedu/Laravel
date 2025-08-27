<?php

namespace ISS\App\Domain\Exam;

use InvalidArgumentException;
use ISS\App\Domain\Exam\ValueObjects\CheckedQuestion;
use ISS\App\Domain\Exam\ValueObjects\ExamQuestionText;
use ISS\App\Domain\Exam\ValueObjects\ExamAnswerText;
use ISS\App\Domain\Exam\ValueObjects\QuestionWithAnswersWithText;


/**
 * @var array<CheckedQuestion> $checkedQuestions массив проверенных экз.вопросов
 * @var array<ExamQuestionText> $examQuestionTexts массив текстовых значений для экзаменационных вопросов
 * @var array<ExamAnswerText> $examAnswersText массив текстовых значений для ответов на экзам-е вопросы
 * @var array<QuestionWithAnswersWithText> $examBlank массив данных заполненного блакна экзамена
 */

class ExamBlank
{
    private array $checkedQuestions;
    private array $examQuestionTexts;
    private array $examAnswersText;

    private array $examBlank;

    public function  __construct(array $checkedQuestions, array $examQuestionTexts, array $examAnswersText)
    {
        //инициализируем переменную для массива проверенных экзаменационных вопросов
        $this->checkedQuestions = array_map(
            function ($question) {
                return new CheckedQuestion($question['questionId'], $question['answerId'], $question['rightAnswerId']);
            },
            $checkedQuestions
        );

        //инициализируем переменную для массива текстов экзаменационных вопросов
        $this->examQuestionTexts = array_map(
            function ($question) {
                return new ExamQuestionText($question['id'], $question['question']);
            },
            $examQuestionTexts
        );

        //инициализируем переменную для массива текстов ответов на экзаменационные вопросы
        $this->examAnswersText = array_map(
            function ($answer) {
                return new ExamAnswerText($answer['id'], empty($answer['answer']) ? null : $answer['answer']); //220825--здесь сделал по условию!!!!
            },
            $examAnswersText
        );
    }

    //ГЕТТЕРЫ СЕТТЕРЫ

    /**
     * Получить заполненный бланк экзамена
     * @return array<QuestionWithAnswersWithText>
     */
    public function getExamBlank(): array
    {
        return $this->examBlank;
    }

    //МУТАТОРЫ

    /**
     * Сформировать заполненный бланк экзамена (с текстами вопросов ответов и правильных ответов)
     * для отправки на проверку преподавателю
     * @return void
     */
    public function makeExamFilledBlank(): void
    {
        //проверяем что экзаменационные вопросы есть (а ответов может и не быть если все вопросы сложные)
        if (!empty($this->examQuestionTexts)) {
            //получаем все id вопросов
            $questionIndexes = array_map(
                function ($tmp) {
                    return $tmp->id;
                },
                $this->examQuestionTexts
            );

            //получаем все id ответов
            $answerIndexes = array_map(
                function ($tmp) {
                    return $tmp->id;
                },
                $this->examAnswersText
            );

            foreach ($this->checkedQuestions as $question) {
                $indexOfQuestion = array_search($question->questionId, $questionIndexes);
                if (is_numeric($question->answerId)) {
                    $indexOfAnswer = array_search($question->answerId, $answerIndexes);
                } else {
                    $indexOfAnswer = null;
                }

                if (is_numeric($question->rightAnswerId)) {
                    $indexOfRightAnswer = array_search($question->rightAnswerId, $answerIndexes);
                } else {
                    $indexOfRightAnswer = null;
                }
                $this->examBlank[] = new QuestionWithAnswersWithText(
                    questionId: $question->questionId,
                    questionText: ($this->examQuestionTexts[$indexOfQuestion])->question,
                    answerId: $question->answerId,
                    answerText: !is_null($indexOfAnswer) && ($indexOfAnswer !== false) ? ($this->examAnswersText[$indexOfAnswer])->answer : null,
                    rightAnswerId: $question->rightAnswerId,
                    rightAnswerText: !is_null($indexOfRightAnswer) && ($indexOfRightAnswer !==false ) ? ($this->examAnswersText[$indexOfRightAnswer])->answer : null,
                );
            }
        }
    }

    //БИЗНЕС ПРАВИЛА

}
