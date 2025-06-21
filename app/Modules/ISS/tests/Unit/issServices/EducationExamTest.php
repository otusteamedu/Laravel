<?php

namespace App\Modules\ISS\tests\Unit\issServices;

//use PHPUnit\Framework\TestCase;
use App\Modules\ISS\tests\TestCase;
use Mockery\MockInterface;
use PHPUnit\Framework\Attributes\Group;
use App\Modules\ISS\src\Services\EducationExam\EducationExamRepoInterface;
use App\Modules\ISS\src\Services\EducationExam\chooseCheckType\ChooseCheckType;
use App\Modules\ISS\src\Services\EducationExam\chooseCheckType\InputDTO as chooseInputDTO;
use App\Modules\ISS\src\Services\EducationExam\chooseCheckType\OutputDTO as chooseOutputDTO;
use App\Modules\ISS\src\Services\EducationExam\isExamComplicated\IsExamComplicated;
use App\Modules\ISS\src\Services\EducationExam\isExamComplicated\InputDTO as complicatedInputDTO;
use App\Modules\ISS\src\Services\EducationExam\isExamComplicated\OutputDTO as complicatedOutputDTO;
use App\Modules\ISS\src\Services\EducationExam\getExamQuestions\GetExamQuestions;
use App\Modules\ISS\src\Services\EducationExam\getExamQuestions\InputDTO as getQuestionInputDTO;
use App\Modules\ISS\src\Services\EducationExam\getExamQuestions\OutputDTO as getQuestionOutputDTO;
use App\Modules\ISS\src\Services\EducationExam\getExamQuestions\AnswerDTO;
use App\Modules\ISS\src\Services\EducationExam\isExamCanBePassed\IsExamCanBePassed;
use App\Modules\ISS\src\Services\EducationExam\isExamCanBePassed\InputDTO as canBePassedInputDTO;
use App\Modules\ISS\src\Services\EducationExam\isExamCanBePassed\OutputDTO as canBePassedOutputDTO;
use App\Modules\ISS\src\Services\EducationExam\checkSimpleExam\CheckSimpleExam;
use App\Modules\ISS\src\Services\EducationExam\checkSimpleExam\InputDTO as checkSimpleExamInputDTO;
use App\Modules\ISS\src\Services\EducationExam\checkSimpleExam\OutputDTO as checkSimpleExamOutputDTO;
use App\Modules\ISS\src\Services\EducationExam\markExamPassedForUser\MarkExamPassedForUser;
use App\Modules\ISS\src\Services\EducationExam\markExamPassedForUser\InputDTO as markExamInputDTO;
use App\Modules\ISS\src\Services\EducationExam\markExamPassedForUser\OutputDTO as markExamOutputDTO;
use App\Modules\ISS\src\Services\EducationExam\chooseExamCheckTeacher\ChooseExamCheckTeacher;
use App\Modules\ISS\src\Services\EducationExam\chooseExamCheckTeacher\InputDTO as examTeacherInputDTO;
use App\Modules\ISS\src\Services\EducationExam\chooseExamCheckTeacher\OutputDTO as examTeacherOutputDTO;
use App\Modules\ISS\src\Services\EducationExam\getUserAndPointDataByCheckCode\GetUserAndPointDataByCheckCode;
use App\Modules\ISS\src\Services\EducationExam\getUserAndPointDataByCheckCode\InputDTO as checkCodeInputDTO;
use App\Modules\ISS\src\Services\EducationExam\getUserAndPointDataByCheckCode\OutputDTO as checkCodeOutputDTO;
use App\Modules\ISS\src\Services\EducationExam\delCheckCode\DelCheckCode;
use App\Modules\ISS\src\Services\EducationExam\delCheckCode\InputDTO as delCheckCodeInputDTO;
use App\Modules\ISS\src\Services\EducationExam\delCheckCode\OutputDTO as delCheckCodeOutputDTO;
use App\Modules\ISS\src\Services\EducationExam\makeCheckCode\MakeCheckCode;
use App\Modules\ISS\src\Services\EducationExam\makeCheckCode\InputDTO as makeCheckCodeInputDTO;
use App\Modules\ISS\src\Services\EducationExam\makeCheckCode\OutputDTO as makeCheckCodeOutputDTO;
use App\Modules\ISS\src\Services\EducationExam\processTeacherFeedback\ProcessTeacherFeedback;
use App\Modules\ISS\src\Services\EducationExam\processTeacherFeedback\InputDTO as processInputDTO;
use App\Modules\ISS\src\Services\EducationExam\processTeacherFeedback\OutputDTO as processOutputDTO;
use App\Modules\ISS\src\Services\EducationExam\processTeacherFeedback\ExamProcessException;
use App\Modules\ISS\src\Services\EducationExam\processTeacherFeedback\ExamCheckCodeDelException;
use App\Modules\ISS\src\Services\EducationExam\processTeacherFeedback\ExamCheckCodeNotFoundException;
use App\Modules\ISS\src\Services\EducationExam\getCheckCodeByUserIdAndRealPointId\GetCheckCodeByUserIdAndRealPointId;
use App\Modules\ISS\src\Services\EducationExam\getCheckCodeByUserIdAndRealPointId\InputDTO as findCheckCodeInputDTO;
use App\Modules\ISS\src\Services\EducationExam\getCheckCodeByUserIdAndRealPointId\OutputDTO as findCheckCodeOutputDTO;

use App\Modules\ISS\src\Services\issUser\IssUserRepoInterface;
use App\Modules\ISS\src\Services\issUser\getUserData\GetUserData;
use App\Modules\ISS\src\Services\issUser\getUserData\InputDTO as userInputDTO;

class EducationExamTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function tearDown(): void
    {
        parent::tearDown();
    }

    /**
     * Проверка что если в экзамене есть вопросы без вариантов ответа то он сложный, а если нет то простой
     */
    #[Group(name: "isExamComplicated")]
    public function test_is_exam_complicated_service()
    {
        //простой экзамен
        $fakeRepoExamSimple = $this->mock(EducationExamRepoInterface::class, function (MockInterface $mock) {
            $mock->shouldReceive('complicatedQuestionsCount')->once()
                ->andReturn(['countOfComplicatedQuestions' => 0]);
        });

        $testedService = new isExamComplicated($fakeRepoExamSimple);
        $result = $testedService(new complicatedInputDTO(id: 0));
        $this->assertInstanceOf(complicatedOutputDTO::class, $result, 'Wrong type of result!');
        $this->assertSame(false, $result->isComplicated, 'Exam must have type: simple');

        //сложный экзамен
        $fakeRepoExamComplicated = $this->mock(EducationExamRepoInterface::class, function (MockInterface $mock) {
            $mock->shouldReceive('complicatedQuestionsCount')->once()
                ->andReturn(['countOfComplicatedQuestions' => 1]);
        });

        $testedService = new isExamComplicated($fakeRepoExamComplicated);
        $result = $testedService(new complicatedInputDTO(id: 0));
        $this->assertInstanceOf(complicatedOutputDTO::class, $result, 'Wrong type of result!');
        $this->assertSame(true, $result->isComplicated, 'Exam must have type: complicated');

        //в сервисе произошла ошибка
        $fakeRepoExamComplicated = $this->createMock(EducationExamRepoInterface::class);
        $fakeRepoExamComplicated->method('complicatedQuestionsCount')
                ->will($this->throwException(new \Exception()));

        $testedService = new isExamComplicated($fakeRepoExamComplicated);
        $this->assertThrows(
            function () use ($testedService) {
                $result = $testedService(new complicatedInputDTO(id: 0));
            },
            \Exception::class
        );
    }

    /**
     * Проверка что для сложного теста должен устанавливаться тип проверки 'manual', а для простого 'auto'
     */
    #[Group(name: "chooseCheckType")]
    public function test_choose_check_type_service()
    {
        //тип проверки преподавателем ('manual')
        $fakeServiceComplicated = $this->mock(IsExamComplicated::class, function (MockInterface $mock) {
            $mock->shouldReceive('__invoke')->once()
                ->andReturn(new complicatedOutputDTO(isComplicated: true));
        });

        $testedService = new ChooseCheckType($fakeServiceComplicated);
        $result = $testedService(new chooseInputDTO(id: 0));
        $this->assertInstanceOf(chooseOutputDTO::class, $result, 'Wrong type of result!');
        $this->assertSame('manual', $result->checkType, 'Check type must be manual');

        //тип проверки автоматическая ('auto')
        $fakeServiceSimple = $this->mock(IsExamComplicated::class, function (MockInterface $mock) {
            $mock->shouldReceive('__invoke')->once()
                ->andReturn(new complicatedOutputDTO(isComplicated: false));
        });

        $testedService = new ChooseCheckType($fakeServiceSimple);
        $result = $testedService(new chooseInputDTO(id: 0));
        $this->assertInstanceOf(chooseOutputDTO::class, $result, 'Wrong type of result!');
        $this->assertSame('auto', $result->checkType, 'Check type must be auto');

        //в сервисе произошла ошибка
        $fakeService = $this->createMock(IsExamComplicated::class);
        $fakeService->method('__invoke')->will($this->throwException(new \Exception()));

        $testedService = new ChooseCheckType($fakeService);
        $this->assertThrows(
            function () use ($testedService) {
                $result = $testedService(new chooseInputDTO(id: 0));
            },
            \Exception::class
        );
    }

    /**
     * Проверка что сервис возвращает заданную структуру данных для вопросов и ответов
     * на экзаменационные вопросы
     */
    #[Group(name: "getExamQuestions")]
    public function test_get_exam_questions_service()
    {
        //сервис отработал правильно
        $fakeRepo = $this->mock(EducationExamRepoInterface::class, function (MockInterface $mock) {
            $mock->shouldReceive('getExamQuestions')->once()
                ->andReturn(
                    [
                        ['rerp_id'=>51, 'erp_id'=>7, 'questionId'=>25, 'questionName'=>'t25', 'questionText'=>'text25'],
                        ['rerp_id'=>51, 'erp_id'=>7, 'questionId'=>26, 'questionName'=>'t26', 'questionText'=>'text26'],
                        ['rerp_id'=>51, 'erp_id'=>7, 'questionId'=>27, 'questionName'=>'t27', 'questionText'=>'text27']
                    ]
                );
            $mock->shouldReceive('getExamAnswers')->times(3)
                ->andReturn(
                    [
                        ['id' => 345, 'answer' => 'goodAnswer'],
                        ['id' => 346, 'answer' => 'wrongAnswer'],
                    ]
                );
        });

        $testedService = new GetExamQuestions($fakeRepo);
        $result = $testedService(new getQuestionInputDTO(id: 0));

        $this->assertIsArray($result, 'Wrong type of result!');
        $this->assertCount(3, $result, 'Result must hawe 3 items');
        foreach ($result as $item) {
            $this->assertInstanceOf(getQuestionOutputDTO::class, $item, 'Wrong type of result item!');
            $this->assertSame($item->rerpId, 51, 'Wrong real route point id');
            $this->assertSame($item->erpId, 7, 'Wrong education rout point id (refs)');
            $this->assertNotNull($item->questionId, 'Missing question id');
            $this->assertNotNull($item->questionName, 'Missing question name');
            $this->assertNotNull($item->questionText, 'Missing question text');
            $this->assertCount(2, $item->answers, 'Must be 2 answers on every question');
            foreach ($item->answers as $answer) {
                $this->assertNotNull($answer->answer, 'Missing answer text');
                $this->assertNotNull($answer->id, 'Missing answer id');
            }
        }

        //в сервисе произошла ошибка (при поиске вопросов к экзамену)
        $fakeRepo = $this->createMock(EducationExamRepoInterface::class);
        $fakeRepo->method('getExamQuestions')->will($this->throwException(new \Exception()));

        $testedService = new GetExamQuestions($fakeRepo);
        $result = $testedService(new getQuestionInputDTO(id: 0));

        $this->assertIsArray($result, 'When error in repository must return array');
        $this->assertEmpty($result, 'When error in repository must return empty array');

        //в сервисе произошла ошибка (при поиске ответов к вопросу)
        $fakeRepo = $this->createMock(EducationExamRepoInterface::class);
        $fakeRepo->method('getExamAnswers')->will($this->throwException(new \Exception()));

        $testedService = new GetExamQuestions($fakeRepo);
        $result = $testedService(new getQuestionInputDTO(id: 0));

        $this->assertIsArray($result, 'When error in repository must return array');
        $this->assertEmpty($result, 'When error in repository must return empty array');
    }

    /**
     * Проверка что сервис возвращает заданную структуру данных для возможности проверки экзамена
     */
    #[Group(name: "isExamCanBePassed")]
    public function test_is_exam_can_be_passed_service()
    {
        //сервис отработал правильно (экзамен разрешено сдать)
        $fakeRepo = $this->mock(EducationExamRepoInterface::class, function (MockInterface $mock) {
            $mock->shouldReceive('isPreviousExamPassed')->once()->andReturn([['valid' => 1]]);
        });

        $testedService = new IsExamCanBePassed($fakeRepo);
        $result = $testedService(new canBePassedInputDTO(issUserId: 0, realRoutePointId: 0));

        $this->assertInstanceOf(canBePassedOutputDTO::class, $result, 'Wrong type of result!');
        $this->assertTrue($result->grantPassExam, 'Must be TRUE');

        //сервис отработал правильно (экзамен запрешено сдавать)
        $fakeRepo = $this->mock(EducationExamRepoInterface::class, function (MockInterface $mock) {
            $mock->shouldReceive('isPreviousExamPassed')->once()->andReturn([['valid' => 0]]);
        });

        $testedService = new IsExamCanBePassed($fakeRepo);
        $result = $testedService(new canBePassedInputDTO(issUserId: 0, realRoutePointId: 0));

        $this->assertInstanceOf(canBePassedOutputDTO::class, $result, 'Wrong type of result!');
        $this->assertFalse($result->grantPassExam, 'Must be FALSE');


        //сервис отработал правильно (экзамен сдавать разрешено, для первой крайней точки маршрута)
        $fakeRepo = $this->mock(EducationExamRepoInterface::class, function (MockInterface $mock) {
            $mock->shouldReceive('isPreviousExamPassed')->once()->andReturn([]);
        });

        $testedService = new IsExamCanBePassed($fakeRepo);
        $result = $testedService(new canBePassedInputDTO(issUserId: 0, realRoutePointId: 0));

        $this->assertInstanceOf(canBePassedOutputDTO::class, $result, 'Wrong type of result!');
        $this->assertTrue($result->grantPassExam, 'Must be TRUE');


        //в сервисе произошла ошибка
        $fakeRepo = $this->createMock(EducationExamRepoInterface::class);
        $fakeRepo->method('isPreviousExamPassed')->will($this->throwException(new \Exception()));

        $testedService = new IsExamCanBePassed($fakeRepo);
        $result = $testedService(new canBePassedInputDTO(issUserId: 0, realRoutePointId: 0));

        $this->assertInstanceOf(canBePassedOutputDTO::class, $result, 'Wrong type of result!');
        $this->assertFalse($result->grantPassExam, 'Must be FALSE');
    }

    /**
     * Проверка что сервис возвращает заданную структуру данных для результата проверки простого экзамена
     */
    #[Group(name: "checkSimpleExam")]
    public function test_check_simple_exam_service()
    {
        //сервис отработал верно (экзамент сдан без ошибочных ответов)
        $dataFromExamForm = [
            ['questionId' => 8, 'answerId' => 34],
            ['questionId' => 5, 'answerId' => 1],
            ['questionId' => 3, 'answerId' => 25]
        ];
        $fakeRepo = $this->mock(EducationExamRepoInterface::class, function (MockInterface $mock) {
            $mock->shouldReceive('getQuestionsWithAnswers')->once()->andReturn(
                [
                    [
                        'id' => 5,
                        'question' => 't5',
                        'exam_answer' => [
                            ['id' => 22, 'answer' => 'a22', 'is_right' => 'N'],
                            ['id' => 221, 'answer' => 'a221', 'is_right' => 'N'],
                            ['id' => 1, 'answer' => 'a1', 'is_right' => 'Y'],
                        ],
                    ],
                    [
                        'id' => 8,
                        'question' => 't8',
                        'exam_answer' => [
                            ['id' => 34, 'answer' => 'a8', 'is_right' => 'Y'],
                            ['id' => 33, 'answer' => 'a1', 'is_right' => 'N'],
                        ],
                    ],
                    [
                        'id' => 3,
                        'question' => 't3',
                        'exam_answer' => [
                            ['id' => 38, 'answer' => 'a8', 'is_right' => 'N'],
                            ['id' => 25, 'answer' => 'a25', 'is_right' => 'Y'],
                        ],
                    ],
                ]
            );
        });

        $testedService = new CheckSimpleExam($fakeRepo);
        $result = $testedService(new checkSimpleExamInputDTO(errorsAllowed: 40, questionsWithAnswers: $dataFromExamForm));

        $this->assertInstanceOf(checkSimpleExamOutputDTO::class, $result, 'Wrong type of result!');
        $this->assertTrue($result->passed, 'Exam must be passed!');


        //сервис отработал верно (экзамент сдан с допустимым количеством ошибочных ответов)
        $dataFromExamForm = [
            ['questionId' => 8, 'answerId' => 34],
            ['questionId' => 5, 'answerId' => 777],
            ['questionId' => 3, 'answerId' => 25]
        ];
        $fakeRepo = $this->mock(EducationExamRepoInterface::class, function (MockInterface $mock) {
            $mock->shouldReceive('getQuestionsWithAnswers')->once()->andReturn(
                [
                    [
                        'id' => 5,
                        'question' => 't5',
                        'exam_answer' => [
                            ['id' => 22, 'answer' => 'a22', 'is_right' => 'N'],
                            ['id' => 221, 'answer' => 'a221', 'is_right' => 'N'],
                            ['id' => 1, 'answer' => 'a1', 'is_right' => 'Y'],
                        ],
                    ],
                    [
                        'id' => 8,
                        'question' => 't8',
                        'exam_answer' => [
                            ['id' => 34, 'answer' => 'a8', 'is_right' => 'Y'],
                            ['id' => 33, 'answer' => 'a1', 'is_right' => 'N'],
                        ],
                    ],
                    [
                        'id' => 3,
                        'question' => 't3',
                        'exam_answer' => [
                            ['id' => 38, 'answer' => 'a8', 'is_right' => 'N'],
                            ['id' => 25, 'answer' => 'a25', 'is_right' => 'Y'],
                        ],
                    ],
                ]
            );
        });

        $testedService = new CheckSimpleExam($fakeRepo);
        $result = $testedService(new checkSimpleExamInputDTO(errorsAllowed: 40, questionsWithAnswers: $dataFromExamForm));

        $this->assertInstanceOf(checkSimpleExamOutputDTO::class, $result, 'Wrong type of result!');
        $this->assertTrue($result->passed, 'Exam must be passed!');

        //сервис отработал верно (экзамент сдан с количеством ошибочных ответов больше допустимого -- экзамент провален)
        $dataFromExamForm = [
            ['questionId' => 8, 'answerId' => 34],
            ['questionId' => 5, 'answerId' => 777],
            ['questionId' => 3, 'answerId' => 777]
        ];
        $fakeRepo = $this->mock(EducationExamRepoInterface::class, function (MockInterface $mock) {
            $mock->shouldReceive('getQuestionsWithAnswers')->once()->andReturn(
                [
                    [
                        'id' => 5,
                        'question' => 't5',
                        'exam_answer' => [
                            ['id' => 22, 'answer' => 'a22', 'is_right' => 'N'],
                            ['id' => 221, 'answer' => 'a221', 'is_right' => 'N'],
                            ['id' => 1, 'answer' => 'a1', 'is_right' => 'Y'],
                        ],
                    ],
                    [
                        'id' => 8,
                        'question' => 't8',
                        'exam_answer' => [
                            ['id' => 34, 'answer' => 'a8', 'is_right' => 'Y'],
                            ['id' => 33, 'answer' => 'a1', 'is_right' => 'N'],
                        ],
                    ],
                    [
                        'id' => 3,
                        'question' => 't3',
                        'exam_answer' => [
                            ['id' => 38, 'answer' => 'a8', 'is_right' => 'N'],
                            ['id' => 25, 'answer' => 'a25', 'is_right' => 'Y'],
                        ],
                    ],
                ]
            );
        });

        $testedService = new CheckSimpleExam($fakeRepo);
        $result = $testedService(new checkSimpleExamInputDTO(errorsAllowed: 40, questionsWithAnswers: $dataFromExamForm));

        $this->assertInstanceOf(checkSimpleExamOutputDTO::class, $result, 'Wrong type of result!');
        $this->assertFalse($result->passed, 'Exam must be fail!');

        //в сервисе произошла ошибка (в репозитории исключение или из базы пришел пустой массив)
        $dataFromExamForm = [
            ['questionId' => 8, 'answerId' => 34],
            ['questionId' => 5, 'answerId' => 777],
            ['questionId' => 3, 'answerId' => 777]
        ];
        $fakeRepo = $this->mock(EducationExamRepoInterface::class, function (MockInterface $mock) {
            $mock->shouldReceive('getQuestionsWithAnswers')->once()->andReturn([]);
        });

        $testedService = new CheckSimpleExam($fakeRepo);
        $result = $testedService(new checkSimpleExamInputDTO(errorsAllowed: 40, questionsWithAnswers: $dataFromExamForm));

        $this->assertNull($result, 'Wrong type of result!');

        //в сервисе произошла ошибка (из в массиве из базы не хватает хотя бы одного вопроса)
        $dataFromExamForm = [
            ['questionId' => 8, 'answerId' => 34],
            ['questionId' => 5, 'answerId' => 777],
            ['questionId' => 3, 'answerId' => 25]
        ];
        $fakeRepo = $this->mock(EducationExamRepoInterface::class, function (MockInterface $mock) {
            $mock->shouldReceive('getQuestionsWithAnswers')->once()->andReturn(
                [
                    [
                        'id' => 8,
                        'question' => 't8',
                        'exam_answer' => [
                            ['id' => 34, 'answer' => 'a8', 'is_right' => 'Y'],
                            ['id' => 33, 'answer' => 'a1', 'is_right' => 'N'],
                        ],
                    ],
                    [
                        'id' => 3,
                        'question' => 't3',
                        'exam_answer' => [
                            ['id' => 38, 'answer' => 'a8', 'is_right' => 'N'],
                            ['id' => 25, 'answer' => 'a25', 'is_right' => 'Y'],
                        ],
                    ],
                ]
            );
        });

        $testedService = new CheckSimpleExam($fakeRepo);
        $result = $testedService(new checkSimpleExamInputDTO(errorsAllowed: 40, questionsWithAnswers: $dataFromExamForm));

        $this->assertNull($result, 'Wrong type of result!');
    }

    /**
     * Проверка что сервис возвращает заданную структуру данных для постановки отметки что экзамен сдан
     */
    #[Group(name: "markExamPassedForUser")]
    public function test_mark_exam_passed_for_user_service()
    {
        //сервис отработал верно
        $fakeRepo = $this->mock(EducationExamRepoInterface::class, function (MockInterface $mock) {
            $mock->shouldReceive('getRealRouteIdForRealPointBelongs')
                ->with(\Mockery::type('array'))->once()->andReturn(['reru_id' => 2345678]);
            $mock->shouldReceive('updateLastPassPoint')
                ->with(\Mockery::type('array'))->once()->andReturn(true);
        });

        $testedService = new MarkExamPassedForUser($fakeRepo);
        $result = $testedService(new markExamInputDTO(issUserId: 0, realRoutePointId: 0));

        $this->assertInstanceOf(markExamOutputDTO::class, $result, 'Wrong type of result!');
        $this->assertTrue($result->result, 'Must be true!');

        //в сервисе произошла ошибка (при поиске id маршрута)
        $fakeRepo = $this->createMock(EducationExamRepoInterface::class);
        $fakeRepo->method('getRealRouteIdForRealPointBelongs')->will($this->throwException(new \Exception()));
        $fakeRepo->method('updateLastPassPoint')->with(\Mockery::type('array'))->willReturn(true);

        $testedService = new MarkExamPassedForUser($fakeRepo);
        $result = $testedService(new markExamInputDTO(issUserId: 0, realRoutePointId: 0));

        $this->assertInstanceOf(markExamOutputDTO::class, $result, 'Wrong type of result!');
        $this->assertFalse($result->result, 'Must be false!');

        //в сервисе произошла ошибка (при обновлении last_pass_point_id маршрута)
        $fakeRepo = $this->createMock(EducationExamRepoInterface::class);
        $fakeRepo->method('getRealRouteIdForRealPointBelongs')
                ->with(\Mockery::type('array'))->willReturn(['reru_id' => 2345678]);
        $fakeRepo->method('updateLastPassPoint')->will($this->throwException(new \Exception()));

        $testedService = new MarkExamPassedForUser($fakeRepo);
        $result = $testedService(new markExamInputDTO(issUserId: 0, realRoutePointId: 0));

        $this->assertInstanceOf(markExamOutputDTO::class, $result, 'Wrong type of result!');
        $this->assertFalse($result->result, 'Must be false!');
    }

    /**
     * Проверка что сервис возвращает заданную структуру данных для выбора преподавателя
     * , который будет проверять экзамен
     */
    #[Group(name: "chooseExamCheckTeacher")]
    public function test_choose_exam_check_teacher_service()
    {
        //сервис отработал правильно (преподаватели найдены и выбран 1)
        $fakeRepoUser = $this->mock(IssUserRepoInterface::class, function (MockInterface $mock) {
            $mock->shouldReceive('getUserData')->once()
                ->andReturn(['organization' => 'org1', 'userRole' => null]);
        });
        $userDataGetter = new GetUserData($fakeRepoUser);

        $fakeRepo = $this->mock(EducationExamRepoInterface::class, function (MockInterface $mock) {
            $mock->shouldReceive('getTeachersOfOrganization')->once()
                ->andReturn([['teacher_email' => 'mail1'], ['teacher_email' => 'mail2'], ['teacher_email' => 'mail3']]);
        });

        $testedService = new ChooseExamCheckTeacher($fakeRepo, $userDataGetter);
        $result = $testedService(new examTeacherInputDTO(issUserId: 0));

        $this->assertInstanceOf(examTeacherOutputDTO::class, $result, 'Wrong type of result!');
        $this->assertIsString($result->email, 'Must be string!');
        $this->assertTrue(in_array($result->email, ['mail1', 'mail2', 'mail3']));


        //сервис отработал правильно (найден 1 и выбран 1)
        $fakeRepoUser = $this->mock(IssUserRepoInterface::class, function (MockInterface $mock) {
            $mock->shouldReceive('getUserData')->once()
                ->andReturn(['organization' => 'org1', 'userRole' => null]);
        });
        $userDataGetter = new GetUserData($fakeRepoUser);

        $fakeRepo = $this->mock(EducationExamRepoInterface::class, function (MockInterface $mock) {
            $mock->shouldReceive('getTeachersOfOrganization')->once()
                ->andReturn([['teacher_email' => 'mail1']]);
        });

        $testedService = new ChooseExamCheckTeacher($fakeRepo, $userDataGetter);
        $result = $testedService(new examTeacherInputDTO(issUserId: 0));

        $this->assertInstanceOf(examTeacherOutputDTO::class, $result, 'Wrong type of result!');
        $this->assertSame('mail1', $result->email, 'Wrong email');


        //сервис отработал правильно (преподаватели не найдены)
        $fakeRepoUser = $this->mock(IssUserRepoInterface::class, function (MockInterface $mock) {
            $mock->shouldReceive('getUserData')->once()
                ->andReturn(['organization' => 'org1', 'userRole' => null]);
        });
        $userDataGetter = new GetUserData($fakeRepoUser);

        $fakeRepo = $this->mock(EducationExamRepoInterface::class, function (MockInterface $mock) {
            $mock->shouldReceive('getTeachersOfOrganization')->once()
                ->andReturn([]);
        });

        $testedService = new ChooseExamCheckTeacher($fakeRepo, $userDataGetter);
        $result = $testedService(new examTeacherInputDTO(issUserId: 0));

        $this->assertInstanceOf(examTeacherOutputDTO::class, $result, 'Wrong type of result!');
        $this->assertNull($result->email, 'Must be null!');


        //в сервисе произошла ошибка (при поиске пользователя)
        $fakeRepoUser = $this->createMock(IssUserRepoInterface::class);
        $fakeRepoUser->method('getUserData')->will($this->throwException(new \Exception()));
        $userDataGetter = new GetUserData($fakeRepoUser);

        $fakeRepo = $this->mock(EducationExamRepoInterface::class, function (MockInterface $mock) {
            $mock->shouldReceive('getTeachersOfOrganization')->times(0)->andReturn([]);
        });

        $testedService = new ChooseExamCheckTeacher($fakeRepo, $userDataGetter);
        $result = $testedService(new examTeacherInputDTO(issUserId: 0));

        $this->assertInstanceOf(examTeacherOutputDTO::class, $result, 'Wrong type of result!');
        $this->assertNull($result->email, 'Must be null!');


        //в сервисе произошла ошибка (при поиске преподавателей )
        $fakeRepoUser = $this->mock(IssUserRepoInterface::class, function (MockInterface $mock) {
            $mock->shouldReceive('getUserData')->once()
                ->andReturn(['organization' => 'org1', 'userRole' => null]);
        });
        $userDataGetter = new GetUserData($fakeRepoUser);

        $fakeRepo = $this->createMock(EducationExamRepoInterface::class);
        $fakeRepo->method('getTeachersOfOrganization')->will($this->throwException(new \Exception()));

        $testedService = new ChooseExamCheckTeacher($fakeRepo, $userDataGetter);
        $result = $testedService(new examTeacherInputDTO(issUserId: 0));

        $this->assertInstanceOf(examTeacherOutputDTO::class, $result, 'Wrong type of result!');
        $this->assertNull($result->email, 'Must be null!');
    }

    /**
     * Проверка что сервис возвращает заданную структуру данных для получения кодов пользователя ИОС и точки маршрута
     * по коду проверки преподавателя
     */
    #[Group(name: "getUserAndPointDataByCheckCode")]
    public function test_get_user_and_point_data_by_check_code_service()
    {
        //сервис отработал правильно (нашел код пользователя и код реальной точки маршрута)
        $fakeRepo = $this->mock(EducationExamRepoInterface::class, function (MockInterface $mock) {
            $mock->shouldReceive('getUserAndPointDataByCheckCode')->with(\Mockery::type('array'))
                ->once()->andReturn(['iss_user_id' => 0, 'real_route_point_id' => 0]);
        });

        $testedService = new GetUserAndPointDataByCheckCode($fakeRepo);
        $result = $testedService(new checkCodeInputDTO(examCheckCode: 0));

        $this->assertInstanceOf(checkCodeOutputDTO::class, $result, 'Wrong type of result!');
        $this->assertObjectHasProperty('issUserId', $result, 'Property issUserId missing!');
        $this->assertObjectHasProperty(
            'realRoutePointId',
            $result,
            'Property realRoutePointId missing!'
        );

        //сервис отработал без ошибок но ничего не нашел
        $fakeRepo = $this->mock(EducationExamRepoInterface::class, function (MockInterface $mock) {
            $mock->shouldReceive('getUserAndPointDataByCheckCode')->with(\Mockery::type('array'))
                ->once()->andReturn([]);
        });

        $testedService = new GetUserAndPointDataByCheckCode($fakeRepo);
        $result = $testedService(new checkCodeInputDTO(examCheckCode: 0));

        $this->assertNull($result, 'Wrong type of result!');

        //в сервисе произошла ошибка (исключение в репозитории)
        $fakeRepo = $this->createMock(EducationExamRepoInterface::class);
        $fakeRepo->method('getUserAndPointDataByCheckCode')->will($this->throwException(new \Exception()));

        $testedService = new GetUserAndPointDataByCheckCode($fakeRepo);
        $result = $testedService(new checkCodeInputDTO(examCheckCode: 0));

        $this->assertNull($result, 'Wrong type of result!');
    }

    /**
     * Проверка что сервис возвращает заданную структуру данных для получения кодов пользователя ИОС и точки маршрута
     * по коду проверки преподавателя
     */
    #[Group(name: "пetCheckCodeByUserIdAndRealPointId")]
    public function test_get_check_code_by_userId_and_realPointId_service()
    {
        //сервис отработал правильно (нашел код пользователя и код реальной точки маршрута)
        $fakeRepo = $this->mock(EducationExamRepoInterface::class, function (MockInterface $mock) {
            $mock->shouldReceive('getCheckCodeByUserIdAndRealPointId')->with(\Mockery::type('array'))
                ->once()->andReturn(['exam_check_code' => 0]);
        });

        $testedService = new GetCheckCodeByUserIdAndRealPointId($fakeRepo);
        $result = $testedService(new findCheckCodeInputDTO(issUserId: 0, realRoutePointId: 0));

        $this->assertInstanceOf(findCheckCodeOutputDTO::class, $result, 'Wrong type of result!');
        $this->assertObjectHasProperty('examCheckCode', $result, 'Property examCheckCode missing!');


        //сервис отработал без ошибок но ничего не нашел
        $fakeRepo = $this->mock(EducationExamRepoInterface::class, function (MockInterface $mock) {
            $mock->shouldReceive('getCheckCodeByUserIdAndRealPointId')->with(\Mockery::type('array'))
                ->once()->andReturn([]);
        });

        $testedService = new GetCheckCodeByUserIdAndRealPointId($fakeRepo);
        $result = $testedService(new findCheckCodeInputDTO(issUserId: 0, realRoutePointId: 0));

        $this->assertNull($result, 'Wrong type of result!');

        //в сервисе произошла ошибка (исключение в репозитории)
        $fakeRepo = $this->createMock(EducationExamRepoInterface::class);
        $fakeRepo->method('getCheckCodeByUserIdAndRealPointId')->will($this->throwException(new \Exception()));

        $testedService = new GetCheckCodeByUserIdAndRealPointId($fakeRepo);
        $result = $testedService(new findCheckCodeInputDTO(issUserId: 0, realRoutePointId: 0));

        $this->assertNull($result, 'Wrong type of result!');
    }


    /**
     * Проверка что сервис возвращает заданную структуру данных при удалении проверочного кода преподавателя
     */
    #[Group(name: "delCheckCode")]
    public function test_del_check_code_service()
    {
        //сервис отработал правильно
        $fakeRepo = $this->mock(EducationExamRepoInterface::class, function (MockInterface $mock) {
            $mock->shouldReceive('delCheckCode')->with(\Mockery::type('array'))
                ->once()->andReturn(true);
        });

        $testedService = new DelCheckCode($fakeRepo);
        $result = $testedService(new delCheckCodeInputDTO(examCheckCode: 0));

        $this->assertInstanceOf(delCheckCodeOutputDTO::class, $result, 'Wrong type of result!');
        $this->assertTrue($result->result);

        //в сервисе произошла ошибка
        $fakeRepo = $this->createMock(EducationExamRepoInterface::class);
        $fakeRepo->method('delCheckCode')->will($this->throwException(new \Exception()));

        $testedService = new DelCheckCode($fakeRepo);
        $result = $testedService(new delCheckCodeInputDTO(examCheckCode: 0));

        $this->assertInstanceOf(delCheckCodeOutputDTO::class, $result, 'Wrong type of result!');
        $this->assertFalse($result->result);
    }

    /**
     * Проверка что сервис возвращает заданную структуру данных при удалении проверочного кода преподавателя
     */
    #[Group(name: "makeCheckCode")]
    public function test_make_check_code_service()
    {
        //сервис отработал правильно
        $fakeRepo = $this->mock(EducationExamRepoInterface::class, function (MockInterface $mock) {
            $mock->shouldReceive('makeCheckCode')->with(\Mockery::type('array'))
                ->once()->andReturn(true);
        });

        $testedService = new MakeCheckCode($fakeRepo);
        $result = $testedService(new makeCheckCodeInputDTO(issUserId: 0, realRoutePointId: 0));

        $this->assertInstanceOf(makeCheckCodeOutputDTO::class, $result, 'Wrong type of result!');
        $this->assertIsString($result->examCheckCode, 'Must be string!');

        //в сервисе произошла ошибка
        $fakeRepo = $this->createMock(EducationExamRepoInterface::class);
        $fakeRepo->method('makeCheckCode')->will($this->throwException(new \Exception()));

        $testedService = new MakeCheckCode($fakeRepo);
        $result = $testedService(new makeCheckCodeInputDTO(issUserId: 0, realRoutePointId: 0));

        $this->assertInstanceOf(makeCheckCodeOutputDTO::class, $result, 'Wrong type of result!');
        $this->assertNull($result->examCheckCode, 'Must be null!');
    }

    /**
     * Проверка что сервис возвращает заданную структуру данных при обработке ответа от преподавателя
     */
    #[Group(name: "processTeacherFeedback")]
    public function test_process_teacher_feedback_service()
    {
        //сервис отработал правильно
        $fakeGetDataByCheckCodeService = $this->createMock(GetUserAndPointDataByCheckCode::class);
        $fakeGetDataByCheckCodeService->method('__invoke')->willReturn(
            new checkCodeOutputDTO(issUserId: 0, realRoutePointId: 0)
        );

        $fakeMarkExamPassedService = $this->createMock(MarkExamPassedForUser::class);
        $fakeMarkExamPassedService->method('__invoke')->willReturn(
            new markExamOutputDTO(result: true)
        );

        $fakeDelCheckCodeService = $this->createMock(DelCheckCode::class);
        $fakeDelCheckCodeService->method('__invoke')->willReturn(
            new delCheckCodeOutputDTO(result: true)
        );

        $testedService = new ProcessTeacherFeedback(
            $fakeGetDataByCheckCodeService,
            $fakeMarkExamPassedService,
            $fakeDelCheckCodeService
        );

        //экзамен сдан
        $result = $testedService(
            new processInputDTO(
                examCheckCode: 0, examComment: 'good', examCheckResult: config('iss.EXAM_STATUS.passed')
            )
        );

        $this->assertInstanceOf(processOutputDTO::class, $result, 'Wrong type of result!');
        $this->assertSame(
            __('iss::issNotify.examPassed'). ' (' . __('iss::issNotify.teacherComment') .
            'good' . ')',
            $result->examResult, 'Not equals string!'
        );

        //экзамен провален
        $result = $testedService(
            new processInputDTO(
                examCheckCode: 0, examComment: null, examCheckResult: config('iss.EXAM_STATUS.failed')
            )
        );

        $this->assertInstanceOf(processOutputDTO::class, $result, 'Wrong type of result!');
        $this->assertSame(
            __('iss::issNotify.examFailed'). ' (' . __('iss::issNotify.teacherComment') .
            '' . ')',
            $result->examResult, 'Not equals string!'
        );


        //в сервисе возникла ошибка (не найдены коды пользователя и точки маршрута)
        $fakeGetDataByCheckCodeService = $this->createMock(GetUserAndPointDataByCheckCode::class);
        $fakeGetDataByCheckCodeService->method('__invoke')->willReturn(null);

        $fakeMarkExamPassedService = $this->createMock(MarkExamPassedForUser::class);
        $fakeMarkExamPassedService->method('__invoke')->willReturn(
            new markExamOutputDTO(result: true)
        );

        $fakeDelCheckCodeService = $this->createMock(DelCheckCode::class);
        $fakeDelCheckCodeService->method('__invoke')->willReturn(
            new delCheckCodeOutputDTO(result: true)
        );

        $testedService = new ProcessTeacherFeedback(
            $fakeGetDataByCheckCodeService,
            $fakeMarkExamPassedService,
            $fakeDelCheckCodeService
        );


        $this->assertThrows(
            function () use ($testedService) {
                $testedService(
                    new processInputDTO(
                        examCheckCode: 0, examComment: 'good', examCheckResult: 'passed'
                    )
                );
            },
            ExamCheckCodeNotFoundException::class
        );


        //в сервисе возникла ошибка (не удалось записать экзамен как пройденный)
        $fakeGetDataByCheckCodeService = $this->createMock(GetUserAndPointDataByCheckCode::class);
        $fakeGetDataByCheckCodeService->method('__invoke')->willReturn(
            new checkCodeOutputDTO(issUserId: 0, realRoutePointId: 0)
        );

        $fakeMarkExamPassedService = $this->createMock(MarkExamPassedForUser::class);
        $fakeMarkExamPassedService->method('__invoke')->willReturn(new markExamOutputDTO(result: false));


        $fakeDelCheckCodeService = $this->createMock(DelCheckCode::class);
        $fakeDelCheckCodeService->method('__invoke')->willReturn(
            new delCheckCodeOutputDTO(result: true)
        );

        $testedService = new ProcessTeacherFeedback(
            $fakeGetDataByCheckCodeService,
            $fakeMarkExamPassedService,
            $fakeDelCheckCodeService
        );

        $this->assertThrows(
            function () use ($testedService) {
                $testedService(
                    new processInputDTO(
                        examCheckCode: 0, examComment: 'good', examCheckResult: 'passed'
                    )
                );
            },
            ExamProcessException::class
        );



        //в сервисе возникла ошибка (не удалось удалить одноразовый код проверки экзамена)
        $fakeGetDataByCheckCodeService = $this->createMock(GetUserAndPointDataByCheckCode::class);
        $fakeGetDataByCheckCodeService->method('__invoke')->willReturn(
            new checkCodeOutputDTO(issUserId: 0, realRoutePointId: 0)
        );

        $fakeMarkExamPassedService = $this->createMock(MarkExamPassedForUser::class);
        $fakeMarkExamPassedService->method('__invoke')->willReturn(
            new markExamOutputDTO(result: true)
        );

        $fakeDelCheckCodeService = $this->createMock(DelCheckCode::class);
        $fakeDelCheckCodeService->method('__invoke')->willReturn(
            new delCheckCodeOutputDTO(result: false)
        );

        $testedService = new ProcessTeacherFeedback(
            $fakeGetDataByCheckCodeService,
            $fakeMarkExamPassedService,
            $fakeDelCheckCodeService
        );

        $this->assertThrows(
            function () use ($testedService) {
                $testedService(
                    new processInputDTO(
                        examCheckCode: 0, examComment: 'good', examCheckResult: 'passed'
                    )
                );
            },
            ExamCheckCodeDelException::class
        );
    }
}
