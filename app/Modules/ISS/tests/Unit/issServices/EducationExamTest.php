<?php

namespace App\Modules\ISS\tests\Unit\issServices;

//use PHPUnit\Framework\TestCase;
use App\Modules\ISS\tests\TestCase;
use Mockery\MockInterface;
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
        $result = $testedService->isExamComplicated(new complicatedInputDTO(id: 0));
        $this->assertInstanceOf(complicatedOutputDTO::class, $result, 'Wrong type of result!');
        $this->assertSame(false, $result->isComplicated, 'Exam must have type: simple');

        //сложный экзамен
        $fakeRepoExamComplicated = $this->mock(EducationExamRepoInterface::class, function (MockInterface $mock) {
            $mock->shouldReceive('complicatedQuestionsCount')->once()
                ->andReturn(['countOfComplicatedQuestions' => 1]);
        });

        $testedService = new isExamComplicated($fakeRepoExamComplicated);
        $result = $testedService->isExamComplicated(new complicatedInputDTO(id: 0));
        $this->assertInstanceOf(complicatedOutputDTO::class, $result, 'Wrong type of result!');
        $this->assertSame(true, $result->isComplicated, 'Exam must have type: complicated');

        //в сервисе произошла ошибка
        $fakeRepoExamComplicated = $this->createMock(EducationExamRepoInterface::class);
        $fakeRepoExamComplicated->method('complicatedQuestionsCount')
                ->will($this->throwException(new \Exception()));

        $testedService = new isExamComplicated($fakeRepoExamComplicated);
        $this->assertThrows(
            function () use ($testedService) {
                $result = $testedService->isExamComplicated(new complicatedInputDTO(id: 0));
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
            $mock->shouldReceive('isExamComplicated')->once()
                ->andReturn(new complicatedOutputDTO(isComplicated: true));
        });

        $testedService = new ChooseCheckType($fakeServiceComplicated);
        $result = $testedService->chooseCheckType(new chooseInputDTO(id: 0));
        $this->assertInstanceOf(chooseOutputDTO::class, $result, 'Wrong type of result!');
        $this->assertSame('manual', $result->checkType, 'Check type must be manual');

        //тип проверки автоматическая ('auto')
        $fakeServiceSimple = $this->mock(IsExamComplicated::class, function (MockInterface $mock) {
            $mock->shouldReceive('isExamComplicated')->once()
                ->andReturn(new complicatedOutputDTO(isComplicated: false));
        });

        $testedService = new ChooseCheckType($fakeServiceSimple);
        $result = $testedService->chooseCheckType(new chooseInputDTO(id: 0));
        $this->assertInstanceOf(chooseOutputDTO::class, $result, 'Wrong type of result!');
        $this->assertSame('auto', $result->checkType, 'Check type must be auto');

        //в сервисе произошла ошибка
        $fakeService = $this->createMock(IsExamComplicated::class);
        $fakeService->method('isExamComplicated')->will($this->throwException(new \Exception()));

        $testedService = new ChooseCheckType($fakeService);
        $this->assertThrows(
            function () use ($testedService) {
                $result = $testedService->chooseCheckType(new chooseInputDTO(id: 0));
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
        $result = $testedService->getExamQuestions(new getQuestionInputDTO(id: 0));

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
        $result = $testedService->getExamQuestions(new getQuestionInputDTO(id: 0));

        $this->assertIsArray($result, 'When error in repository must return array');
        $this->assertEmpty($result, 'When error in repository must return empty array');

        //в сервисе произошла ошибка (при поиске ответов к вопросу)
        $fakeRepo = $this->createMock(EducationExamRepoInterface::class);
        $fakeRepo->method('getExamAnswers')->will($this->throwException(new \Exception()));

        $testedService = new GetExamQuestions($fakeRepo);
        $result = $testedService->getExamQuestions(new getQuestionInputDTO(id: 0));

        $this->assertIsArray($result, 'When error in repository must return array');
        $this->assertEmpty($result, 'When error in repository must return empty array');
    }
}
