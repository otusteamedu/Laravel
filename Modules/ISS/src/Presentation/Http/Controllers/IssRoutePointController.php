<?php

namespace ISS\App\Presentation\Http\Controllers;

use Illuminate\View\View;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use ISS\App\Application\Services\AppServices\RealEducationRoutePoint\GetRealPointMainData\GetRealPointMainData;
use ISS\App\Application\Services\AppServices\RealEducationRoutePoint\GetRealPointMainData\InputDTO as pointMainDataDTO;
use ISS\App\Application\Services\AppServices\Exam\IsExamComplicated\IsExamComplicated;
use ISS\App\Application\Services\AppServices\Exam\IsExamComplicated\InputDTO as examComplicatedDTO;
use ISS\App\Application\Services\AppServices\Exam\GetExamQuestions\GetExamQuestions;
use ISS\App\Application\Services\AppServices\Exam\GetExamQuestions\InputDTO as examQuestionsDTO;
use ISS\App\Application\Services\AppServices\RealEducationRoutePoint\GetFilesOfRealPointData\GetFilesOfRealPointData;
use ISS\App\Application\Services\AppServices\RealEducationRoutePoint\GetFilesOfRealPointData\InputDTO as filesDTO;

/**
 * Контроллер страницы для реальной точки обучающего маршрута
 * содержит:
 * - метод для отображения страницы
 */

class IssRoutePointController extends Controller
{
    /**
     * Отображение страницы
     * @param GetRealPointMainData $getRealPointMainData сервис
     * @param IsExamComplicated $isExamComplicated сервис
     * @param GetExamQuestions $getExamQuestions сервис
     * @param GetFilesOfRealPointData $getFilesOfRealPointData сервис
     * @param int $issUserId код пользователя ИОС
     * @param int $routeId код реального обучающего маршрута пользователя
     * @param int $pointId код реальной точки обучающего маршрута
     * @return View
     */
    public function educationRoutePoint(
        GetRealPointMainData    $getRealPointMainData,
        IsExamComplicated       $isExamComplicated,
        GetExamQuestions        $getExamQuestions,
        GetFilesOfRealPointData $getFilesOfRealPointData,
        int                     $issUserId,
        int                     $routeId,
        int                     $pointId
    ): View
    {
        //получаем данные из сервисов
        $pointMainData = Cache::tags(['pointData', 'mainPointData'])->remember(
            'mainPointData_' . $issUserId . '_' . $pointId,
            60*5,
            function () use ($pointId, $issUserId, $getRealPointMainData) {
                return $getRealPointMainData(
                    new pointMainDataDTO(id: $pointId, userDataId: $issUserId)
                );
            }
        );

        if (is_null($pointMainData) || $pointMainData->routePointId != $pointId) {
            abort(404);
        }

        $isComplicated = Cache::tags(['pointData', 'pointExam'])->remember(
            'pointExam_' . $pointId,
            60*60,
            function () use ($pointId, $isExamComplicated) {
                return $isExamComplicated(new examComplicatedDTO($pointId));
            }
        );

        $examQuestionsWithAnswers = Cache::tags(['pointData', 'pointExam', 'questionsWithAnswers'])->remember(
            'questionsWithAnswers_' . $pointId,
            60*60,
            function () use ($pointId, $getExamQuestions) {
                return $getExamQuestions(new examQuestionsDTO($pointId));
            }
        );


        $educationMaterials = Cache::tags(['pointData', 'pointMaterials'])->remember(
            'pointMaterials_' . $pointId,
            60*60,
            function () use ($pointId, $getFilesOfRealPointData) {
                return $getFilesOfRealPointData(new filesDTO($pointId))->materials;
            }
        );

        //переводим в требуемый вид (там где необходимо)
        //экзаменационные вопросы и варианты ответов (для простых вопросов)
        $questions = [];
        foreach ($examQuestionsWithAnswers as $question) {
            $questions[] = [
                'questionId' => $question->questionId,
                'questionName' => $question->questionName,
                'questionText' => $question->questionText,
                'answers' => []
            ];

            foreach ($question->answers as $answer) {
                $questions[count($questions) - 1]['answers'][] = ['id' => $answer->id, 'answer' => $answer->answer];
            }
        }

        //файлы обучающих материалов
        $educationMaterialsFiles = [];
        foreach ($educationMaterials as $materialType => $materials) {
            foreach ($materials as $item) {
                $educationMaterialsFiles[$materialType][$item['title']] = $item['file_path'];
            }
        }


        //формируем данные для страницы
        $pointData = [
            'userId' => $issUserId,

            'routeId' => $routeId,
            'pointId' => $pointId,
            'routeName' => $pointMainData->routeName,
            'pointName' =>  $pointMainData->pointName,
            'examResult' => $pointMainData->examResult,
            'examDate' => Carbon::parse($pointMainData->examDate)->format('d-m-Y'),

            'isExamComplicated' => $isComplicated->isComplicated,
            'questions' => $questions,

            'textFileTypes' => config('iss.ALLOWED_EDUCATION_TEXT_MATERIAL_TYPES'),
            'videoFileTypes' => config('iss.ALLOWED_EDUCATION_VIDEO_MATERIAL_TYPES'),
            'materials' => $educationMaterialsFiles,
        ];

        return view('iss::issNodePage', ['pointData' => $pointData]);
    }
}
