<?php

namespace ISS\Tests\Unit\issServices;

//use PHPUnit\Framework\TestCase;
//use ISS\tests\TestCase;
use Illuminate\Foundation\Testing\TestCase;
use Mockery\MockInterface;
use PHPUnit\Framework\Attributes\Group;
use ISS\App\Application\Services\AppServices\NotifyService\GetDataForExamStatusNotify\GetDataForExamStatusNotify;
use ISS\App\Application\Services\AppServices\NotifyService\GetDataForExamStatusNotify\InputDTO;
use ISS\App\Application\Services\AppServices\NotifyService\GetDataForExamStatusNotify\OutputDTO;
use ISS\App\Application\Services\IssUser\GetUserData\GetUserData;
use ISS\App\Application\Services\IssUser\GetUserData\OutputDTO as userOutputDTO;
use ISS\App\Application\Services\AppServices\RealEducationRoutePoint\GetRealPointMainData\GetRealPointMainData;
use ISS\App\Application\Services\AppServices\RealEducationRoutePoint\GetRealPointMainData\OutputDTO as pointUotputDTO;

class NotifyService2Test extends TestCase
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
     * Проверка что сервис возвращает праильную структуру данных для выборки данных,
     * необходимых для бланка уведомления ученика о статусе экзамена
     */
    #[Group(name: "getDataForExamStatusNotify")]
    public function test_get_data_for_exam_status_notify()
    {
        //в сервисе возникла ошибка (при выборке данных пользователя ИОС, сдавшего экзамен)
        $fakeGetUserDataService = $this->createMock(GetUserData::class);
        $fakeGetUserDataService->method('__invoke')->willReturn(null);

        $fakeGetPointDataService = $this->createMock(GetRealPointMainData::class);
        $fakeGetPointDataService->method('__invoke')->willReturn(
            new pointUotputDTO(
                routePointId: 0,
                examDate: '01-01-10',
                routeName: 'route 1',
                pointName: 'point 1',
                lastPassedExamDate: null,
                examResult: 'passed'
            )
        );

        $testedService = new GetDataForExamStatusNotify($fakeGetPointDataService, $fakeGetUserDataService);
        $result = $testedService(new InputDTO(issUserId: 0, realRoutePointId: 0));

        $this->assertNull($result, 'Must be null!');

        //в сервисе возникла ошибка (при выборке данных реальной точки обучающего маршрута)
        $fakeGetUserDataService = $this->createMock(GetUserData::class);
        $fakeGetUserDataService->method('__invoke')->willReturn(
            new userOutputDTO(
                id: null,
                avatarFilePath: null,
                userId: null,
                roleId: null,
                roleName: null,
                organization: null,
                name: null,
                secondName: null,
                lastName: null,
                email: 'TEST',
                createdAt: null,
                updatedAt: null,
                deletedAt: null
            )
        );

        $fakeGetPointDataService = $this->createMock(GetRealPointMainData::class);
        $fakeGetPointDataService->method('__invoke')->willReturn(null);

        $testedService = new GetDataForExamStatusNotify($fakeGetPointDataService, $fakeGetUserDataService);
        $result = $testedService(new InputDTO(issUserId: 0, realRoutePointId: 0));

        $this->assertNull($result, 'Must be null!');


        //сервис отработал правильно
        $fakeGetUserDataService = $this->createMock(GetUserData::class);
        $fakeGetUserDataService->method('__invoke')->willReturn(
            new userOutputDTO(
                id: null,
                avatarFilePath: null,
                userId: null,
                roleId: null,
                roleName: null,
                organization: null,
                name: null,
                secondName: null,
                lastName: null,
                email: 'TEST',
                createdAt: null,
                updatedAt: null,
                deletedAt: null
            )
        );
        $fakeGetPointDataService = $this->createMock(GetRealPointMainData::class);
        $fakeGetPointDataService->method('__invoke')->willReturn(
            new pointUotputDTO(
                routePointId: 0,
                examDate: '01-01-10',
                routeName: 'route 1',
                pointName: 'point 1',
                lastPassedExamDate: null,
                examResult: 'passed'
            )
        );

        $testedService = new GetDataForExamStatusNotify($fakeGetPointDataService, $fakeGetUserDataService);
        $result = $testedService(new InputDTO(issUserId: 0, realRoutePointId: 0));

        $this->assertInstanceOf(OutputDTO::class, $result, 'Wrong result type!');
        $this->assertObjectHasProperty('userEmail', $result);
        $this->assertObjectHasProperty('routeName', $result);
        $this->assertObjectHasProperty('pointName', $result);
        $this->assertObjectHasProperty('examData', $result);

        $this->assertEquals(
            date('Y-m-d', strtotime('01-01-10')),
            date('Y-m-d', strtotime($result->examData))
        );
        //echo date('d-m-Y', strtotime('10-01-01'));

    }
}
