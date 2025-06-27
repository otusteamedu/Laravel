<?php

namespace App\Modules\ISS\src\Services\EducationExam\processExamCheck;

use App\Modules\ISS\src\Services\EducationExam\processExamCheck\InputDTO;
use App\Modules\ISS\src\Services\EducationExam\processExamCheck\OutputDTO;
use App\Modules\ISS\src\Services\EducationExam\processExamCheck\ExamAlreadyOnCheckException;
use App\Modules\ISS\src\Services\EducationExam\processExamCheck\ExamCanNotBePassedException;
use App\Modules\ISS\src\Services\EducationExam\processExamCheck\CanNotSendExamToTeacherException;
use App\Modules\ISS\src\Services\EducationExam\processExamCheck\CanNotMarkExamPassedException;
use App\Modules\ISS\src\Services\EducationExam\processExamCheck\WrongCheckTypeException;
use App\Modules\ISS\src\Services\EducationExam\getCheckCodeByUserIdAndRealPointId\GetCheckCodeByUserIdAndRealPointId;
use App\Modules\ISS\src\Services\EducationExam\getCheckCodeByUserIdAndRealPointId\InputDTO as findCheckCodeInputDTO;
use App\Modules\ISS\src\Services\EducationExam\isExamCanBePassed\IsExamCanBePassed;
use App\Modules\ISS\src\Services\EducationExam\isExamCanBePassed\InputDTO as examCanBePassInputDTO;
use App\Modules\ISS\src\Services\EducationExam\chooseCheckType\ChooseCheckType;
use App\Modules\ISS\src\Services\EducationExam\chooseCheckType\InputDTO as checkTypeInputDTO;
use App\Modules\ISS\src\Services\EducationExam\checkSimpleExam\CheckSimpleExam;
use App\Modules\ISS\src\Services\EducationExam\checkSimpleExam\InputDTO as checkSimpleExamInputDTO;
use App\Modules\ISS\src\Services\EducationExam\markExamPassedForUser\MarkExamPassedForUser;
use App\Modules\ISS\src\Services\EducationExam\markExamPassedForUser\InputDTO as markPassedInputDTO;
use App\Modules\ISS\src\Services\EducationExam\chooseExamCheckTeacher\ChooseExamCheckTeacher;
use App\Modules\ISS\src\Services\EducationExam\chooseExamCheckTeacher\InputDTO as chooseTeacherInputDTO;
use App\Modules\ISS\src\Services\EducationExam\makeCheckCode\MakeCheckCode;
use App\Modules\ISS\src\Services\EducationExam\makeCheckCode\InputDTO as makeCheckCodeInputDTO;
use App\Modules\ISS\src\Services\EducationExam\fillExamBlank\FillExamBlank;
use App\Modules\ISS\src\Services\EducationExam\fillExamBlank\InputDTO as fillExamBlankInputDTO;

class ProcessExamCheck
{
    /** @var GetCheckCodeByUserIdAndRealPointId $getCheckCodeByUserIdAndRealPointId сервис (получить код проверки препода) */
    private GetCheckCodeByUserIdAndRealPointId $getCheckCodeByUserIdAndRealPointId;

    /** @var IsExamCanBePassed $isExamCanBePassed сервис (проверка можно ли экзамен сдать) */
    private IsExamCanBePassed                  $isExamCanBePassed;

    /** @var ChooseCheckType $chooseCheckType сервис (выбор типа проверки) */
    private ChooseCheckType                    $chooseCheckType;

    /** @var CheckSimpleExam $checkSimpleExam сервис (авто проверка простых вопросов экзамена) */
    private CheckSimpleExam                    $checkSimpleExam;

    /** @var MarkExamPassedForUser $markExamPassedForUser сервис (отметить экзамен сдан для пользователя) */
    private MarkExamPassedForUser              $markExamPassedForUser;

    /** @var ChooseExamCheckTeacher $chooseExamCheckTeacher сервис (выбрать препода для ручной проверки экзамена) */
    private ChooseExamCheckTeacher             $chooseExamCheckTeacher;

    /** @var MakeCheckCode $makeCheckCode сервис (создать одноразовый код проверки экзамена для преподавателя) */
    private MakeCheckCode                      $makeCheckCode;

    /** @var FillExamBlank $fillExamBlank сервис (заполняет бланк экзамена для отправки преподавателю на проверку) */
    private FillExamBlank                      $fillExamBlank;

    public function __construct(
        GetCheckCodeByUserIdAndRealPointId $getCheckCodeByUserId,
        IsExamCanBePassed                  $isExamCanBePassed,
        ChooseCheckType                    $chooseCheckType,
        CheckSimpleExam                    $checkSimpleExam,
        MarkExamPassedForUser              $markExamPassedForUser,
        ChooseExamCheckTeacher             $chooseExamCheckTeacher,
        MakeCheckCode                      $makeCheckCode,
        FillExamBlank                      $fillExamBlank,
    )
    {
        $this->getCheckCodeByUserIdAndRealPointId = $getCheckCodeByUserId;
        $this->isExamCanBePassed = $isExamCanBePassed;
        $this->chooseCheckType = $chooseCheckType;
        $this->checkSimpleExam = $checkSimpleExam;
        $this->markExamPassedForUser = $markExamPassedForUser;
        $this->chooseExamCheckTeacher = $chooseExamCheckTeacher;
        $this->makeCheckCode = $makeCheckCode;
        $this->fillExamBlank = $fillExamBlank;
    }


    /**
     * Обрабатывает проверку экзамена при отправке его из формы на странице
     * точка обучающего маршрута пользователя
     * @param InputDTO $inputDTO
     * @return OutputDTO
     * @throws ExamAlreadyOnCheckException
     * @throws ExamCanNotBePassedException
     * @throws CanNotSendExamToTeacherException
     * @throws CanNotMarkExamPassedException
     * @throws WrongCheckTypeException
     */
    public function __invoke(InputDTO $inputDTO): OutputDTO
    {
        //проверить что экзамен не находится уже на проверке
        //(нет записи для этой точки маршрута и пользоваателя ИОС в таблице одноразовых кодов преподователя)
        $checkCodeExists = ($this->getCheckCodeByUserIdAndRealPointId)(
            new findCheckCodeInputDTO(
                issUserId: $inputDTO->issUserId,
                realRoutePointId: $inputDTO->realRoutePointId
            )
        );
        if ($checkCodeExists) {
            throw new ExamAlreadyOnCheckException();
        }

        //проверить что экзамен разрешено сдать
        $examCanBePassed = ($this->isExamCanBePassed)(
            new examCanBePassInputDTO(
                issUserId: $inputDTO->issUserId,
                realRoutePointId: $inputDTO->realRoutePointId
            )
        );

        if (!$examCanBePassed->grantPassExam) {
            throw new ExamCanNotBePassedException();
        }

        //выбрать тип проверки экзамена
        $examCheckType = ($this->chooseCheckType)(new checkTypeInputDTO(id: $inputDTO->realRoutePointId));
        if ($examCheckType->checkType == config('iss.EXAM_CHECK_TYPE.manual')) {
            //проверка преподавателем
            //echo '  PREPOD  ';

            //вызвать сервис определяющий какому преподу отправить заполненный бланк
            $teacher = ($this->chooseExamCheckTeacher)(
                new chooseTeacherInputDTO(
                    issUserId: $inputDTO->issUserId
                )
            );
            $mail = $teacher->email;

            //получить проверенный список вопросов (для отправки преподавателю)
            $checkedQuestions = ($this->checkSimpleExam)(
                new checkSimpleExamInputDTO(
                    errorsAllowed: config('iss.EXAM_ERRORS_ALLOWED_PERCENT'),
                    questionsWithAnswers: $inputDTO->questionsWithAnswers,
                )
            )->checkedQuestions;

            //вызвать сервис создания кода проверки
            $checkCode = ($this->makeCheckCode)(
                new makeCheckCodeInputDTO(
                    issUserId: $inputDTO->issUserId,
                    realRoutePointId: $inputDTO->realRoutePointId
                )
            );
             $examCheckCode = $checkCode->examCheckCode;

             //сформировать данные для бланка преподавателя
            if (!empty($checkedQuestions) && !is_null($mail) && !is_null($examCheckCode)) {
                //добавить тексты вопросов и ответов в бланк экзамена
                $checkedQuestionsWithText = ($this->fillExamBlank)(
                    new fillExamBlankInputDTO($checkedQuestions)
                );

                $teacherBlankDTO = new TeacherBlankDTO(
                    email: $mail,
                    examCheckCode: $examCheckCode,
                    checkedQuestions: $checkedQuestionsWithText->examBlank
                );
                $examCheckResult = __('iss::issNotify.examSentToTeacher');
            } else {
                throw new CanNotSendExamToTeacherException();
            }
        } else if ($examCheckType->checkType == config('iss.EXAM_CHECK_TYPE.auto')) {
            //автоматическая проверка сервисом
            //echo '  AUTOMAT  ';
            $result = ($this->checkSimpleExam)(
                new checkSimpleExamInputDTO(
                    errorsAllowed: config('iss.EXAM_ERRORS_ALLOWED_PERCENT'),
                    questionsWithAnswers: $inputDTO->questionsWithAnswers,
                )
            )->passed;

            if ($result) {
                //сдан
                //отметить что сдан
                $markResult = ($this->markExamPassedForUser)(
                    new markPassedInputDTO(
                        issUserId: $inputDTO->issUserId,
                        realRoutePointId: $inputDTO->realRoutePointId,
                    )
                );

                if ($markResult) {
                    $examCheckResult = __('iss::issNotify.examPassed');
                } else {
                    throw new CanNotMarkExamPassedException();
                }
            } else {
                //не сдан
                $examCheckResult = __('iss::issNotify.examFailed');
            }
        } else {
            throw new WrongCheckTypeException();
        }
        return new OutputDTO(
            checkType: $examCheckType->checkType,
            examCheckResult: $examCheckResult,
            teacherBlankDTO: $teacherBlankDTO ?? null
        );

    }
}
