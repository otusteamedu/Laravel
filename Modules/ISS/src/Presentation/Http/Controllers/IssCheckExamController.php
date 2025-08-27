<?php

namespace ISS\App\Presentation\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use ISS\App\Application\Services\Exam\ExamCheckCode\GetUserAndPointDataByCheckCode\GetUserAndPointDataByCheckCode;
use ISS\App\Application\Services\Exam\ExamCheckCode\GetUserAndPointDataByCheckCode\InputDTO as checkCodeInputDTO;
use ISS\App\Application\Services\AppServices\Exam\ProcessExamCheck\ProcessExamCheck;
use ISS\App\Application\Services\AppServices\Exam\ProcessExamCheck\InputDTO as processExamCheckInputDTO;
use ISS\App\Application\Services\AppServices\Exam\ProcessTeacherFeedback\ProcessTeacherFeedback;
use ISS\App\Application\Services\AppServices\Exam\ProcessTeacherFeedback\InputDTO as feedbackDTO;
use ISS\App\Application\Services\AppServices\NotifyService\GetDataForExamStatusNotify\GetDataForExamStatusNotify;
use ISS\App\Application\Services\AppServices\NotifyService\GetDataForExamStatusNotify\InputDTO as notifyInputDTO;
use ISS\App\Infrastructure\Events\ExamChecked\ExamChecked;
use ISS\App\Infrastructure\Events\ExamChecked\ExamCheckedDTO;

/**
 * Контроллер для проверки экзаменационных тестов
 * содержит:
 * - главный метод проверки экзамена
 * - метод для отображения формы ввода резульатов проверки экзамена (доступен для преподавателей по signedUrl)
 * - метод для обработки результата проверки экзамена преподавателем (доступен для преподавателей по signedUrl)
 */

class IssCheckExamController extends Controller
{
    /**
     * Метод для проверки экзамена (при отправке учеником формы экзамена на проверку со страницы issRoutePoint)
     * (выбирает тип проверки и выполняет либо проверку простого экзамена, либо отправку на проверку преподу,
     * формирует и отправляет уведомление ученику и заполненный бланк экзамена преподу)
     * @param ProcessExamCheck $processExamCheck сервис (проверка экзамена, если надо генерация данных для преподавателя
     * @param GetDataForExamStatusNotify $getDataForExamStatusNotify сервис (извлеч данные для бланка уведомления ученику)
     * @param Request $request, //должен содержать обязательные поля (issUserId, realEducationRoutePointId)
     * @return
     */
    public function checkExam(
        ProcessExamCheck            $processExamCheck,
        GetDataForExamStatusNotify  $getDataForExamStatusNotify,
        Request                     $request
    )
    {
        //валидация
        $validationRules= [
            'issUserId'                 => 'required',
            'realEducationRoutePointId' => 'required',
        ];
        $validator = Validator::make($request->input(), $validationRules);
        if ($validator->fails()) {
            return json_encode(['error' => __('iss::issNodePage.missingRequiredParameter')]);
        }

        //достать из входного запроса список вопросов экзамена с ответами
        //если вопрос сложный (без варианто ответа) то в answerId пишется его письменный ответ
        $questionsWithAnswers = [];
        foreach ($request->except(['_token', 'issUserId', 'realEducationRoutePointId']) as $key => $value) {
            $questionsWithAnswers[] = [
                'questionId' => str_replace('question_', '', $key),
                'answerId' =>  $value,
            ];
        }

        //проверить экзамен (при необходимости сформировать заполненный бланк экзамена для преподавателя)
        try {
            $examProcessed = $processExamCheck(
                new processExamCheckInputDTO(
                    issUserId: $request->input('issUserId'),
                    realRoutePointId: $request->input('realEducationRoutePointId'),
                    questionsWithAnswers: $questionsWithAnswers
                )
            );
        } catch (\Error | \Exception $e) {
            return json_encode(['error' => $e->getMessage()]);
        }

        //если тип проверки экзамена -- преподавателем
        if ($examProcessed->checkType == config('iss.EXAM_CHECK_TYPE.manual')) {
            $needMailToTeacher = true;

            //создание защищенной ссытки для преподавателя
            $teacherUrl = URL::signedRoute('showCheckForm');//echo json_encode(['url'=>$teacherUrl]);exit;
        }

        //получение основных данных для бланка уведомления ученику
        $mainNotifyData = $getDataForExamStatusNotify(
            new notifyInputDTO(
                issUserId: $request->input('issUserId'),
                realRoutePointId: $request->input('realEducationRoutePointId')
            )
        );

        if (is_null($mainNotifyData)) {
            return json_encode(['error' => __('iss::issNodePage.canNotMakeNotifyForUser')]);
        }

        //событие, по которому отправляются уведомления ученику и преподавателю
        ExamChecked::dispatch(
            new ExamCheckedDTO(
                needMailToTeacher: $needMailToTeacher ?? false,
                teacherEmail: $examProcessed->teacherBlankDTO->email ?? null,
                teacherURL: $teacherUrl ?? null,
                examCheckCode: $examProcessed->teacherBlankDTO->examCheckCode ?? null,
                checkedQuestionsWithText: $examProcessed->teacherBlankDTO->checkedQuestions ?? null,
                studentEmail: $mainNotifyData->userEmail,
                scheduledExamDate: $mainNotifyData->examData,
                pointName: $mainNotifyData->pointName,
                routeName: $mainNotifyData->routeName,
                examCheckResult: $examProcessed->examCheckResult
            )
        );

        //инвалидация кэша
        Cache::tags(['diagram'])->flush();
        Cache::tags(['userData', 'userDataRoutes'])->forget(
            'userDataRoutes_' . $request->input('issUserId')
        );
        Cache::tags(['pointData', 'mainPointData'])->forget('mainPointData_' .
                           $request->input('issUserId') . '_' .
                           $request->input('realEducationRoutePointId')
        );

        return json_encode(['success' => $examProcessed->examCheckResult]);
        //НА ФРОНТЕ ОБНОВИТЬ страницу чтобы если простой экзамен сдан--блокировать кнопку
    }

    /**
     * Отобразить форму для ввода результата проверки экзамена
     * (защищен посредником signed)
     * @return View
     */
    public function showCheckExamForm(): View
    {
        //для отображения сообщения что результат проверки принят учпешно
        if (Session::has('examChecked')) {
            $success = Session::get('examChecked');
        } else {
            $success = false;
        }

        //создаем подпись преподавателя для маршрута отправки результата проверки экзамена
        $teacherSignature = URL::signedRoute('examCheckResult');

        return view('iss::issExamCheckPage', ['success' => $success, 'signedRoute' => $teacherSignature]);
    }

    /**
     * Обработать результат проверки экзамена преподавателем (после того как препод заполнил и отправил форму проверки)
     * @param ProcessTeacherFeedback $processTeacherFeedback сервис (обработать данные от преподавателя)
     * @param GetDataForExamStatusNotify $getDataForExamStatusNotify сервис (извлеч данные для бланка уведомления ученику)
     * @param GetUserAndPointDataByCheckCode $getUserAndPointDataByCheckCode сервис
     *                                       (найти код польз-я ИОС и точки маршрута по коду проверки преподавателя)
     * @param Request $request
     * @return
     */
    public function setExamManualCheckResult(
        ProcessTeacherFeedback         $processTeacherFeedback,
        GetDataForExamStatusNotify     $getDataForExamStatusNotify,
        GetUserAndPointDataByCheckCode $getUserAndPointDataByCheckCode,
        Request                        $request
    )
    {
            $teacherLock = Cache::lock('teacher_lock', 60*60*3);
        if ($teacherLock->get()) {
            //валидация
            $validationRules= [
                'examCheckCode' =>   'required|string|starts_with:exam_code_|between:23,40',
                'examComment'   =>   'string|nullable|max:30',
                'examCheckResult' => 'required|in:' . config('iss.EXAM_STATUS.failed') . ',' . config('iss.EXAM_STATUS.passed'),
            ];

            $errorMessages = [
                'examCheckCode.required' => __('iss::issExamCheckPage.checkCode'),
                'examCheckCode.string' => ':attribute ' . __('iss::issExamCheckPage.mustBeString'),
                'examCheckCode.starts_with' => __('iss::issExamCheckPage.wrongPrefix'),
                'examCheckCode.between' => __('iss::issExamCheckPage.wrongLength') . ' :between :min <-> :max',

                'examComment.string' => __('iss::issExamCheckPage.stringComment'),
                'examComment.max' => __('iss::issExamCheckPage.lengthComment') . ' :max',

                'examCheckResult.required' => __('iss::issExamCheckPage.requiredResult'),
                'examCheckResult.in' => __('iss::issExamCheckPage.resultInArray')
            ];
            $validator = Validator::make(
                $request->input(),
                $validationRules,
                $errorMessages,
                ['examCheckCode'=> __('iss::examCheckCode')]
            );

            try {
                $validated = $validator->validated();
            } catch (\Error | \Exception $e) {
                $teacherLock->release();
                return redirect()->back()->withInput()->withErrors($validator);
            }

            if ($validator->fails()) {
                $teacherLock->release();
                return redirect()->back()->withInput()->withErrors($validator);
            }

            //определение кода точки маршрута и кода пользователя (данные используются только для очистки кэша!)
            $dataForCacheRefresh = $getUserAndPointDataByCheckCode(
                new checkCodeInputDTO(examCheckCode: $validated['examCheckCode'])
            );

            //обработка ответа преподавателя
            try {
                $examResultForNotify = $processTeacherFeedback(
                    new feedbackDTO(
                        examCheckCode: $validated['examCheckCode'],
                        examComment: $validated['examComment'],
                        examCheckResult: $validated['examCheckResult']
                    )
                );
            } catch (\Exception $exception) {
                $teacherLock->release();
                return redirect()->back()->withErrors(['serviceError' => __('iss::issExamCheckPage.serviceError')]);
            }

            //получение основных данных для бланка уведомления ученику
            $mainNotifyData = $getDataForExamStatusNotify(
                new notifyInputDTO(
                    issUserId: $examResultForNotify->issUserId,
                    realRoutePointId: $examResultForNotify->realRoutePointId
                )
            );
            if (is_null($mainNotifyData)) {
                $teacherLock->release();
                return redirect()->back()->withErrors(['serviceError' => __('iss::issExamCheckPage.serviceError')]);
            }


            //отправка уведомления ученику
            //отправка уведомления по email через очередь
            //Mail::send....
            // В бланк письма \ISS\src\Mails\issExamStatusNotify
            // передаю $mainNotifyData-> + $examResultForNotify->examResult
            if ($validated['examCheckResult'] === config('iss.EXAM_STATUS.failed')) {
                $examStatus = __('iss::issNotify.examFailed');
            } elseif ($validated['examCheckResult'] === config('iss.EXAM_STATUS.passed')) {
                $examStatus = __('iss::issNotify.examPassed');
            } else {
                $examStatus = __('iss::issNotify.examWrongStatus');
            }

            //событие, по которому отправляется уведомление ученику
            ExamChecked::dispatch(
                new ExamCheckedDTO(
                    needMailToTeacher: false,
                    teacherEmail: null,
                    teacherURL: null,
                    examCheckCode: null,
                    checkedQuestionsWithText: null,
                    studentEmail: $mainNotifyData->userEmail,
                    scheduledExamDate: $mainNotifyData->examData,
                    pointName: $mainNotifyData->pointName,
                    routeName: $mainNotifyData->routeName,
                    examCheckResult: $examStatus
                )
            );


            //инвалидация кэша
            Cache::tags(['diagram'])->flush();
            Cache::tags(['userData', 'userDataRoutes'])->forget(
                'userDataRoutes_' . $dataForCacheRefresh->issUserId
            );
            Cache::tags(['pointData', 'mainPointData'])
                ->forget('mainPointData_' .
                    $dataForCacheRefresh->issUserId . '_' .
                    $dataForCacheRefresh->realRoutePointId
                );

            Session::flash('examChecked', __('iss::issExamCheckPage.examChecked'));

            $teacherLock->release();
        } else {
            Session::flash('examChecked', __('iss::issExamCheckPage.locked'));
        }

        return redirect()->back();
    }
}
