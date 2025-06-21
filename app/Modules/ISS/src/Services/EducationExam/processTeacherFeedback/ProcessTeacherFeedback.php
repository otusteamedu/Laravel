<?php

namespace App\Modules\ISS\src\Services\EducationExam\processTeacherFeedback;

use App\Modules\ISS\src\Services\EducationExam\processTeacherFeedback\InputDTO;
use App\Modules\ISS\src\Services\EducationExam\processTeacherFeedback\OutputDTO;
use App\Modules\ISS\src\Services\EducationExam\processTeacherFeedback\ExamProcessException;
use App\Modules\ISS\src\Services\EducationExam\processTeacherFeedback\ExamCheckCodeDelException;
use App\Modules\ISS\src\Services\EducationExam\processTeacherFeedback\ExamCheckCodeNotFoundException;
use App\Modules\ISS\src\Services\EducationExam\getUserAndPointDataByCheckCode\GetUserAndPointDataByCheckCode;
use App\Modules\ISS\src\Services\EducationExam\getUserAndPointDataByCheckCode\InputDTO as checkCodeDTO;
use App\Modules\ISS\src\Services\EducationExam\markExamPassedForUser\MarkExamPassedForUser;
use App\Modules\ISS\src\Services\EducationExam\markExamPassedForUser\InputDTO as markPassedInputDTO;
use App\Modules\ISS\src\Services\EducationExam\delCheckCode\DelCheckCode;
use App\Modules\ISS\src\Services\EducationExam\delCheckCode\InputDTO as delCheckCodeInputDTO;

class ProcessTeacherFeedback
{
    private GetUserAndPointDataByCheckCode $getUserAndPointDataByCheckCode;
    private MarkExamPassedForUser          $markExamPassedForUser;
    private DelCheckCode                   $delCheckCode;


    public function __construct(
        GetUserAndPointDataByCheckCode $getUserAndPointDataByCheckCode,
        MarkExamPassedForUser          $markExamPassedForUser,
        DelCheckCode                   $delCheckCode,
    )
    {
        $this->getUserAndPointDataByCheckCode = $getUserAndPointDataByCheckCode;
        $this->markExamPassedForUser = $markExamPassedForUser;
        $this->delCheckCode = $delCheckCode;
    }

    /**
     * Обработать ответ от преподавателя (с результатом проверки экзамена)
     * @param InputDTO $inputDTO
     * @return ?OutputDTO
     * @throws ExamProcessException
     * @throws ExamCheckCodeDelException
     * @throws ExamCheckCodeNotFoundException
     */
    public function __invoke(InputDTO $inputDTO): OutputDTO
    {
        //определение кода пользователя ИОС и кода точки маршрута по коду проверки экзамена
        $issCodes = ($this->getUserAndPointDataByCheckCode)(new checkCodeDTO(examCheckCode: $inputDTO->examCheckCode));
        if (is_null($issCodes)) {
            throw new ExamCheckCodeNotFoundException();
        }

        $issUserId = $issCodes->issUserId;
        $realRoutePointId = $issCodes->realRoutePointId;

        //проверка статуса который поставил преподаватель (сдан\не сдан)
        if ($inputDTO->examCheckResult == config('iss.EXAM_STATUS.passed')) {
            //создание отметки что экзамен сдан
            $result = ($this->markExamPassedForUser)(
                new markPassedInputDTO(issUserId: $issUserId, realRoutePointId: $realRoutePointId)
            );

            if ($result->result) {
                $baseNotifyText = '' . __('iss::issNotify.examPassed');
            } else {
                throw new ExamProcessException();
            }
        } else {
            $baseNotifyText = '' . __('iss::issNotify.examFailed');
        }

        //удалить код проверки код проверки из таблицы
        $result = ($this->delCheckCode)(
            new delCheckCodeInputDTO(examCheckCode: $inputDTO->examCheckCode)
        );
        if (!$result->result) {
            throw new ExamCheckCodeDelException();
        }

        return new OutputDTO(
            examResult: $baseNotifyText .
            ' (' .
            __('iss::issNotify.teacherComment') .
            ($inputDTO->examComment ? $inputDTO->examComment : '') .
            ')',
            issUserId: $issUserId,
            realRoutePointId: $realRoutePointId,
        );
    }
}
