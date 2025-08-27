Зависимости в сервисах

EducationExam\chooseCheckType            => EducationExam\isExamComplicated
EducationExam\chooseExamCheckTeacher     => issUser\getUserData
NotifyService\getDataForExamStatusNotify => issUser\getUserData
                                            EducationRoutePoint\getRealPointMainData
EducationExam\ProcessTeacherFeedback     => EducationExam\getUserAndPointDataByCheckCode
                                            EducationExam\markExamPassedForUser
                                            EducationExam\delCheckCode

App\Modules\ISS\src\Services\EducationExam\processExamCheck =>
                                         => EducationExam\getCheckCodeByUserIdAndRealPointId
                                         => EducationExam\isExamCanBePassed
                                         => EducationExam\chooseCheckType
                                         => EducationExam\checkSimpleExam
                                         => EducationExam\markExamPassedForUser
                                         => EducationExam\chooseExamCheckTeacher
                                         => EducationExam\makeCheckCode
                                         => EducationExam\fillExamBlank

App\Modules\ISS\src\Services\EducationRoutePoint\GetPointData =>
                                         => Exam\EducationQuestion\GetRefPointExamQuestions
                                         => EducationMaterial\GetAllMaterialsOfRefPoint

Services\AppServices\IssUser\DeleteIssUser
                                         => Servises\IssUser\DeleteIssUser

Services\AppServices\RealEducationRoutePoint\GetFilesOfRealPointData
                                         => Services\EducationMaterial\GetMaterialsOfRefPointFilteredByType
                                         => Services\RealEducationRoutePoint\GetRealRoutePointById

