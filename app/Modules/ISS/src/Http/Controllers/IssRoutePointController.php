<?php

namespace App\Modules\ISS\src\Http\Controllers;

use Illuminate\View\View;
use Carbon\Carbon;
use App\Modules\ISS\src\Services\EducationRoutePoint\getRealPointMainData\GetRealPointMainData;
use App\Modules\ISS\src\Services\EducationRoutePoint\getRealPointMainData\InputDTO as pointMainDataDTO;
use App\Modules\ISS\src\Services\EducationExam\isExamComplicated\IsExamComplicated;
use App\Modules\ISS\src\Services\EducationExam\isExamComplicated\InputDTO as examComplicatedDTO;
use App\Modules\ISS\src\Services\EducationExam\getExamQuestions\GetExamQuestions;
use App\Modules\ISS\src\Services\EducationExam\getExamQuestions\InputDTO as examQuestionsDTO;
use App\Modules\ISS\src\Services\EducationRoutePoint\getFilesOfRealPointData\GetFilesOfRealPointData;
use App\Modules\ISS\src\Services\EducationRoutePoint\getFilesOfRealPointData\InputDTO as filesDTO;


class IssRoutePointController extends Controller
{
    /**@var int $userId код пользователя из сессии */
    //public int $userId;

    //public function __construct()
    //{
    //    $this->userId = session()->get('issUser')->issUserId;
    //}

    /**
     * Контроллер страницы для точки обучающего маршрута
     * @param GetRealPointMainData $getRealPointMainData
     * @param IsExamComplicated $isExamComplicated
     * @param GetExamQuestions $getExamQuestions
     * @param GetFilesOfRealPointData $getFilesOfRealPointData
     * @param int $issUserId
     * @param int $routeId
     * @param int $pointId
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
        $pointMainData = $getRealPointMainData->getRealPointMainData(
            new pointMainDataDTO(id: $pointId, userDataId: $issUserId)
        );

        if (is_null($pointMainData) || $pointMainData->routePointId != $pointId) {
            abort(404);
        }

        $isComplicated = $isExamComplicated->isExamComplicated(new examComplicatedDTO($pointId));
        $examQuestionsWithAnswers = $getExamQuestions->getExamQuestions(new examQuestionsDTO($pointId));
        $educationMaterials = $getFilesOfRealPointData->getFilesOfRealPointData(new filesDTO($pointId))->materials;

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
            'materials' => $educationMaterialsFiles,
        ];

        return view('iss::issNodePage', ['pointData' => $pointData]);
    }
}
