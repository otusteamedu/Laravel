<?php

namespace ISS\App\Domain\Exam\ValueObjects;

use InvalidArgumentException;
use ISS\App\Domain\SharedValueObjects\Id;

/**
 * @var int $questionId код экзаменационного вопроса
 * @var string $questionText текст экзаменационного вопроса
 * @var int|string|null $answerId код ответа для экзаменационного вопроса (или письменый ответ для сложного вопроса)
 * @var string|null $answerText текст ответа для экзаменационного вопроса
 * @var int|null $rightAnswerId код правильного ответа для экзаменационного вопроса
 * @var string|null $rightAnswerText текст правильного ответа для экзаменационного вопроса
 */

class QuestionWithAnswersWithText
{
    public int $questionId;
    public string $questionText;
    public int|string|null $answerId;
    public string|null $answerText;
    public int|null $rightAnswerId;
    public string|null $rightAnswerText;

    public function __construct(
        int             $questionId,
        string          $questionText,
        int|string|null $answerId,
        string|null     $answerText,
        int|null        $rightAnswerId,
        string|null     $rightAnswerText,
    )
    {
        $this->questionId = (new Id($questionId))->id;

        if (empty($questionText)) {
            throw new InvalidArgumentException("Question Text can't be empty");
        }
        $this->questionText = $questionText;

        if (!is_null($answerId)) {
            if (is_numeric($answerId)) {
                if ($answerId < 0) { //оставляю здесь возможность ставить 0 т.к. если нет ответа в форме то 0
                    throw new InvalidArgumentException("Answer id must be greater than 0");
                }
            }
        }
        $this->answerId = $answerId;

        if (!is_null($answerText)) {
            if (empty($answerText)) {
                throw new InvalidArgumentException("Answer Text can't be empty");
            }
        }
        $this->answerText = $answerText;

        if (!is_null($rightAnswerId)) {
            if ($rightAnswerId <= 0) {
                throw new InvalidArgumentException("Right aswer id must be greater than 0");
            }
        }
        $this->rightAnswerId = $rightAnswerId;

        if (!is_null($rightAnswerText)) {
            if (empty($rightAnswerText)) {
                throw new InvalidArgumentException("Right Answer Text can't be empty");
            }
        }
        $this->rightAnswerText = $rightAnswerText;
    }
}
