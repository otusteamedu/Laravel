<?php

namespace ISS\App\Domain\Exam;

use ISS\App\Domain\Exam\ValueObjects\Question;
use ISS\App\Domain\Exam\ValueObjects\Answer;
use ISS\App\Domain\Exam\ValueObjects\QuestionAndAnswerFromExamForm;
use ISS\App\Domain\Exam\ValueObjects\ErrorsAllowed;

/**
 * @var array<Question> $rawQuestionsWithAnswersFromDB массив вопросов с ответами полученный из БД
 * @var array<QuestionAndAnswerFromExamForm> $questionsAndRightAnswers массив кодов вопросов и кодов их правильных ответов,
 *                                           созданный на основе массива из БД
 * @var array $questionsWithAnswersFromExamForm массив кодов вопросов и их ответов из формы экзамена с фронта
 * @var int $errorsAllowed процент допустимых ошибок
 *
 * @var array $checkedQuestions массив проверенных экз.вопросов вида
 *            [
 *                [ 'questionId' => , 'answerId' => , 'rightAnswerId' => ],
 *                [ 'questionId' => , 'answerId' => , 'rightAnswerId' => ],
 *                ...
 *            ]
 * @var int|null $examErrors количество ошибок в простом экзамене (подсчитанное по $checkedQuestions)
 * @var bool $examResult отметка о разультате экзамена (true -- сдан, false -- не сдан (или произошла ошибка))
 */

class Exam
{
    private array $rawQuestionsWithAnswersFromDB;
    private array $questionsAndRightAnswers;
    public array $questionsWithAnswersFromExamForm;
    private int $errorsAllowed;

    private array $checkedQuestions;
    private int|null $examErrors;
    private bool $examResult;

    public function __construct(
        array $rawQuestionsWithAnswersFromDB,
        array $questionsWithAnswersFromExamForm,
        int $errorsAllowed
    )
    {
        //создаем массив сырых данных из БД (извлеченных по id переданных из формы запросов)
        $this->rawQuestionsWithAnswersFromDB = array_map(
            function ($question) {
                $answers = array_map(
                    function ($answer) {
                        return new Answer(
                            $answer['id'],
                            $answer['answer_short_name'],
                            $answer['answer'],
                            $answer['question_id'],
                            $answer['is_right']
                        );
                    },
                    $question['exam_answers']
                );

                return new Question(
                    $question['id'],
                    $question['short_question_name'],
                    $question['question'],
                    $question['point_id'],
                    $answers
                );
            },
            $rawQuestionsWithAnswersFromDB
        );

        //создаем массив вопросов и их правильных ответов (на основе массива из БД)
        $this->questionsAndRightAnswers = $this->makeQuestionsWithRightAnswers();

        //создаем массив данных из экзаменационной формы
        $this->questionsWithAnswersFromExamForm = array_map(
            function ($question) {
                return new QuestionAndAnswerFromExamForm(
                    questionId: $question['questionId'],
                    answerId: $question['answerId']
                );
            },
            $questionsWithAnswersFromExamForm
        );

        //задаем допустимый процент ошибок в тесте
        $this->errorsAllowed = (new ErrorsAllowed($errorsAllowed))->errorsAllowed;
    }

    //ГЕТТЕРЫ СЕТТЕРЫ

    /**
     * Получить массив проверенных экзаменационных вопросов
     * @return array
     *          [
     *               ['questionId' => , 'answerId' => , 'rightAnswerId' => ],
     *               ['questionId' => , 'answerId' => , 'rightAnswerId' => ],
     *               .....
     *          ])
     */
    public function getCheckedQuestions(): array
    {
        return $this->checkedQuestions;
    }

    /**
     * Получить количество ошибочных ответов для простого экзамена
     * @return int
     */
    /*public function getExamErrorsCount(): int
    {
        return $this->examErrors;
    }*/

    /**
     * Получить результат проверки простого экзамена
     * @return bool
     */
    public function getExamResult(): bool
    {
        return $this->examResult;
    }


    //МУТАТОРЫ

    /**
     * Преобразует массив экзаменационных вопросов с ответами (полученный из БД) к виду
     * @return array
     *         [
     *             ['questionId'=>Id(int id), 'rightAnswerId' =>],
     *             ['questionId'=>Id(int id), 'rightAnswerId' =>],
     *             ...
     *          ]
     * если вдруг попался вопрос без ответов (сложный) то поставит ему rightAnswerId => null
     * если у простого вопроса с ответами в базе по ошибке нет ответа с is_right = Y то ставит rightAnswerId => 0
     */
    private function makeQuestionsWithRightAnswers(): array
    {
        return array_map(
            function ($tmp) {
                $answerId = null;
                foreach($tmp->answers as $answer) {
                    $answerId = 0;
                    if ($answer->isRight == 'Y') {
                        $answerId = $answer->id->id; //берем не объект Id а сам id из valueObject
                        break;
                    }
                }
                return ['questionId' => $tmp->id, 'rightAnswerId' => $answerId];
            },
            $this->rawQuestionsWithAnswersFromDB
        );
    }

    /**
     * Сформировать массив проверенных экзаменационных вопросов
     * @return array
     * [
     * [ 'questionId' => , 'answerId' => , 'rightAnswerId' => ],
     * [ 'questionId' => , 'answerId' => , 'rightAnswerId' => ],
     * ....
     * ]
     */
    private function makeCheckedQuestions(): array
    {
        $checkedQuestions = [];

        foreach ($this->questionsWithAnswersFromExamForm as $currentQuestion) {
            //ищем id вопроса в массиве вопросов с правильными ответами из БД
            $index = array_search(
                $currentQuestion->questionId,
                array_column($this->questionsAndRightAnswers, 'questionId')
            );

            //проверяем что текущий вопрос из формы есть в списке вопросов из БД
            if ($index !== false) {
                $checkedQuestions[] = [
                    'questionId' => $currentQuestion->questionId->id,
                    'answerId' => $currentQuestion->answerId,
                    'rightAnswerId' => ($this->questionsAndRightAnswers)[$index]['rightAnswerId']
                ];
            } else {
                //запись в лог (ошибка в БД нет одного из запрашиваемых вопросов)
                return [];
            }
        }

        return $checkedQuestions;
    }

    //БИЗНЕС ПРАВИЛА

    /**
     * Проверка правильности решения простого экзамена
     * (составление массива проверенных экзаменационных вопросов,
     * подсчет ошибок, получение статуса результата экзамена)
     */
    public function checkExam(): void
    {
        $this->checkedQuestions = $this->makeCheckedQuestions();

        if (empty($this->checkedQuestions)) {
            $this->examResult = false;
            $this->examErrors = null;
        } else {
            $this->examErrors = $this->countErrors($this->checkedQuestions);
            $this->examResult = $this->isPassed($this->examErrors, $this->checkedQuestions);
        }
    }

    /**
     * Подсчитать количество ошибок в ответах на простой тест
     * (за счет подсчета ошибок в проверенных вопросах)
     * @param array $checkedQuestions вопросы с ответами и с правильными ответами, формат как в $this->checkedQuestions
     * @return int|null
     */
    private function countErrors(array $checkedQuestions): int|null
    {
        if (empty($checkedQuestions)) {
            return null;
        }

        return count(
            array_filter(
                $checkedQuestions,
                function ($item) {
                    if (is_null($item['answerId']) ||        //в форме ошибка
                        is_null($item['rightAnswerId']) ||   //вопрос 'сложный'
                        $item['answerId'] != $item['rightAnswerId'] //ответ на вопрос не правильный
                    ) {
                        return true;
                    } else {
                        return false;
                    }
                }
            )
        );
    }

    /**
     * Прооверка пройден экзамен или нет
     * (пройден, когда количество ошибок меньше допустимого процента и среди экзаменационных вопросов нет сложных,
     * т.е. тех что не имеют вариантов ответа)
     * @param int $errorsCount
     * @param array $checkedQuestions
     * @return bool
     */
    private function isPassed(int $errorsCount, array $checkedQuestions): bool
    {
        //проверяем что на проверку переданы только простые вопросы (с ответами)
        if (in_array(null, array_column($checkedQuestions, 'rightAnswerId'), true)) {
            //передан хотя бы один сложный вопрос
            return false;
        } else {
            //переданы только простые вопросы
            //рассчитываем не привышен ли допустимый процент ошибок
            if (100*($errorsCount/count($checkedQuestions)) < $this->errorsAllowed) {
                return true;
            } else {
                return false;
            }
        }
    }


    /**
     * Проветка разрешено ли сдать экзамен для выбранной контрольной точки
     * @param int $pointId код текущей реальной точки маршрута
     * @param int $validPointId код реальной точки маршрута, для которой выполнены условия возможности сдачи экзамена
     * @return bool
     * если текущая точка является следующей за последней пройденной
     * (или первой точкой маршрута, если еще нет пройденных точек), то ее экзамен можно сдать
     * ??? возможно сюда надо как то занести доставание данных из БД???
     */
    public static function isPointCanBePassed(int $pointId, int $validPointId): bool
    {
        return $pointId == $validPointId;
    }

    /**
     * Проверка сложный экзамен или нет
     * (если кол-во сложных вопросов, тех что без вариантов ответа, больше 0 то сложный)
     * @param int $questionsWithoutAnswers кол-во вопросов без вариантов ответов
     * @return bool
     */
    public static function isExamComplicated(int $questionsWithoutAnswers): bool
    {
        return $questionsWithoutAnswers > 0;
    }
}
