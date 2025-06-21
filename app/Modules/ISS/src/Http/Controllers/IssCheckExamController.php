<?php

namespace App\Modules\ISS\src\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use App\Modules\ISS\src\Services\NotifyService\getDataForExamStatusNotify\GetDataForExamStatusNotify;
use App\Modules\ISS\src\Services\NotifyService\getDataForExamStatusNotify\InputDTO as notifyInputDTO;
use App\Modules\ISS\src\Services\EducationExam\processTeacherFeedback\ProcessTeacherFeedback;
use App\Modules\ISS\src\Services\EducationExam\processTeacherFeedback\InputDTO as feedbackDTO;
use App\Modules\ISS\src\Services\EducationExam\processExamCheck\ProcessExamCheck;
use App\Modules\ISS\src\Services\EducationExam\processExamCheck\InputDTO as processExamCheckInputDTO;

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
     * @param ProcessExamCheck $processExamCheck сервис (проверка экзамена, если надо генерация данных для преподавателя)
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
            'issUserId' =>   'required',
            'realEducationRoutePointId'   =>   'required',
        ];
        $validator = Validator::make($request->input(), $validationRules);
        if ($validator->fails()) {
            return json_encode(['error' => __('iss::issNodePage.missingRequiredParameter')]);
        }


        //достать из входного запроса список вопросов экзамена с ответами
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

        //отправить бланк экзамена на проверку преподу (поставить задачу в очередь)
        /** @TODO доделать контроллер отправкой письма в очередь */
        //отправка уведомления по email через очередь
        //Mail::send....
        // В бланк письма \ISS\src\Mails\issExamStatusNotify
        // передаю $examProcessed->teacherBlankDTO

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

        /** @TODO доделать контроллер отправкой письма в очередь */
        //отправка уведомления по email через очередь
        //Mail::send....
        // В бланк письма \ISS\src\Mails\issExamStatusNotify
        // передаю $mainNotifyData-> + $examProcessed->examCheckResult

        return json_encode(['success' => $examProcessed->examCheckResult]);
        //НА ФРОНТЕ ОБНОВИТЬ страницу чтобы если простой экзамен сдан--блокировать кнопку
    }

    /**
     * Отобразить форму для ввода результата проверки экзамена
     * @return View
     */
    public function showCheckExamForm(): View
    {
        if (Session::has('examChecked')) {
            $success = Session::get('examChecked');
        } else {
            $success = false;
        }

        return view('iss::issExamCheckPage', ['success' => $success]);
    }

    /**
     * Обработать результат проверки экзамена преподавателем (после того как препод заполнил и отправил форму проверки)
     * @param ProcessTeacherFeedback $processTeacherFeedback сервис (обработать данные от преподавателя)
     * @param GetDataForExamStatusNotify $getDataForExamStatusNotify сервис (извлеч данные для бланка уведомления ученику)
     * @param Request $request
     * @return
     */
    public function setExamManualCheckResult(
        ProcessTeacherFeedback         $processTeacherFeedback,
        GetDataForExamStatusNotify     $getDataForExamStatusNotify,
        Request                        $request
    )
    {
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
        $validated = $request->validate($validationRules, $errorMessages, ['examCheckCode'=> __('iss::examCheckCode')]);

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
            return redirect()->back()->withErrors(['serviceError' => __('iss::issExamCheckPage.serviceError')]);
        }

        /** @TODO доделать контроллер отправкой письма в очередь */
        //отправка уведомления по email через очередь
        //Mail::send....
        // В бланк письма \ISS\src\Mails\issExamStatusNotify
        // передаю $mainNotifyData-> + $examResultForNotify->examResult

        Session::flash('examChecked', __('iss::issExamCheckPage.examChecked'));
        return redirect()->back();
    }
}
