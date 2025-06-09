<?php

namespace App\Modules\ISS\tests\Unit\issServices;

//use PHPUnit\Framework\TestCase;
use App\Modules\ISS\tests\TestCase;
use Mockery\MockInterface;
use App\Modules\ISS\src\Services\EducationRoute\EducationRouteRepoInterface;
use App\Modules\ISS\src\Services\EducationRoute\getAllEducationRoutesOfUserWithPoints\GetAllEducationRoutesOfUserWithPoints;
use App\Modules\ISS\src\Services\EducationRoute\getAllEducationRoutesOfUserWithPoints\InputDTO as getRoutesInputDTO;
use App\Modules\ISS\src\Services\EducationRoute\getAllEducationRoutesOfUserWithPoints\OutputDTO as getRoutesOutputDTO;
use App\Modules\ISS\src\Services\EducationRoute\getAllEducationRoutesOfUserWithPoints\PointDTO;
use App\Modules\ISS\src\Services\EducationRoute\getRouteReadyPercentForUsersOfFirm\GetRouteReadyPercentForUsersOfFirm;
use App\Modules\ISS\src\Services\EducationRoute\getRouteReadyPercentForUsersOfFirm\InputDTO as percentInputDTO;
use App\Modules\ISS\src\Services\EducationRoute\getRouteReadyPercentForUsersOfFirm\OutputDTO as percentOutputDTO;

class EducationRouteTest  extends TestCase
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
     * Проверка что сервис возвращает заданную структуру данных для обучающих маршрутов и точек маршрута
     * для заданного пользователя ИОС
     */
    #[Group(name: "getRoutesWithPoints")]
    public function test_get_all_education_routes_of_users_with_points()
    {
        //сервис отработал правильно
        $fakeRepo = $this->mock(EducationRouteRepoInterface::class, function (MockInterface $mock) {
            $mock->shouldReceive('getUserRoutesWithPassPercent')->once()
                ->andReturn(
                    [
                        ['route_id'=>34, 'route_name'=>'r1', 'ready_percent'=>'80',],
                        ['route_id'=>57, 'route_name'=>'r2', 'ready_percent'=>'5',]
                    ]
                );
            $mock->shouldReceive('getAllRoutePointsForUser')->once()
                ->andReturn(
                    [
                        ['route_id'=>34, 'real_route_point_id'=>null,
                            'exam_date'=>'12-01-85', 'point_name'=>'p1', 'pass'=>'passed' ],
                        ['route_id'=>57, 'real_route_point_id'=>502,
                            'exam_date'=>'05-06-26', 'point_name'=>'p2', 'pass'=>'wait' ],
                        ['route_id'=>34, 'real_route_point_id'=>503,
                            'exam_date'=>'27-11-94', 'point_name'=>'p3', 'pass'=>'expired' ]
                    ]
                );
        });

        $testedService = new GetAllEducationRoutesOfUserWithPoints($fakeRepo);
        $result = $testedService->getAllEducationRoutesOfUserWithPoints(new getRoutesInputDTO(id: 0));

        $this->assertIsArray($result, 'Result must have type of Array');
        $this->assertCount(2, $result, 'Must be 2 routes');
        foreach ($result as $route) {
            $this->assertInstanceOf(
                getRoutesOutputDTO::class,
                $route,
                'Every route should have type of getRoutesOutputDTO'
            );
            $this->assertCount(1, $route->points, 'Every route should have 1 point');
            $this->assertNotNull($route->readyPercent, 'Ready percent missing');
            $this->assertNotNull($route->routeName, 'Route name missing');
            $this->assertNotNull($route->routeId, 'Route ID missing');

            $this->assertNotNull(($route->points)[0]->pass, 'Point pass missing');
            $this->assertNotNull(($route->points)[0]->examDate, 'Point exam date missing');
            $this->assertNotNull(($route->points)[0]->realRoutePointId, 'Point real ID missing');
            $this->assertNotNull(($route->points)[0]->routePointName, 'Point name missing');
        }

        //в сервисе возникла ошибка (при извлечении маршрутов)
        $fakeRepo = $this->createMock(EducationRouteRepoInterface::class);
        $fakeRepo->method('getUserRoutesWithPassPercent')->will($this->throwException(new \Exception()));

        $testedService = new GetAllEducationRoutesOfUserWithPoints($fakeRepo);
        $result = $testedService->getAllEducationRoutesOfUserWithPoints(new getRoutesInputDTO(id: 0));

        $this->assertIsArray($result, 'Result must have type of Array');
        $this->assertEmpty($result, 'Must be empty array');

        //в сервисе возникла ошибка (при извлечении точек маршрутов)
        $fakeRepo = $this->createMock(EducationRouteRepoInterface::class);
        $fakeRepo->method('getUserRoutesWithPassPercent')
            ->willReturn([['route_id'=>34, 'route_name'=>'r1', 'ready_percent'=>'80']]);
        $fakeRepo->method('getAllRoutePointsForUser')->will($this->throwException(new \Exception()));

        $testedService = new GetAllEducationRoutesOfUserWithPoints($fakeRepo);
        $result = $testedService->getAllEducationRoutesOfUserWithPoints(new getRoutesInputDTO(id: 0));

        $this->assertIsArray($result, 'Result must have type of Array');
        $this->assertEmpty($result, 'Must be empty array');
    }

    /**
     * Проверка что сервис возвращает заданную структуру данных для диаграмм
     * степени прохождения обучающих маршрутов для пользователей фирмы
     */
    #[Group(name: "getRoutesWithPercents")]
    public function test_get_route_ready_percent_for_users_of_firm()
    {
        $fakeRepo = $this->mock(EducationRouteRepoInterface::class, function (MockInterface $mock) {
            $mock->shouldReceive('getRouteReadyPercentForUsersOfFirm')->once()
                ->andReturn(
                    [
                        ['user_data_id'=>22, 'organization'=>'test1', 'route_name'=>'r1', 'ready_percent'=>8, 'fio'=>'fio22'],
                        ['user_data_id'=>22, 'organization'=>'test1', 'route_name'=>'r2', 'ready_percent'=>9, 'fio'=>'fio22'],
                        ['user_data_id'=>23, 'organization'=>'test1', 'route_name'=>'r2', 'ready_percent'=>90, 'fio'=>'fio23'],
                        ['user_data_id'=>24, 'organization'=>'test2', 'route_name'=>'r2', 'ready_percent'=>100, 'fio'=>'fio24'],
                        ['user_data_id'=>25, 'organization'=>'test2', 'route_name'=>'r1', 'ready_percent'=>0, 'fio'=>'fio25'],
                        ['user_data_id'=>26, 'organization'=>'test2', 'route_name'=>'r1', 'ready_percent'=>12, 'fio'=>'fio26'],
                        ['user_data_id'=>26, 'organization'=>'test2', 'route_name'=>'r3', 'ready_percent'=>29, 'fio'=>'fio26'],
                    ]
                );
        });

        $testedService = new GetRouteReadyPercentForUsersOfFirm($fakeRepo);
        $result = $testedService->getRouteReadyPercentForUsersOfFirm(new percentInputDTO(id: 0, isIssAdmin: false));

        $this->assertIsArray($result, 'Result must have type of Array');
        $this->assertCount(2, $result, 'Must be 2 firms');
        foreach ($result as $firm) {
            $this->assertInstanceOf(percentOutputDTO::class, $firm, 'Wrong result type');
            $this->assertNotNull($firm->organization, 'Organization missing');
            $this->assertIsArray($firm->employees, 'Employees missing');

            if ($firm->organization == 'test1') {

                $this->assertCount(2, $firm->employees, 'Wrong employee count');
                $this->assertCount(2, ($firm->employees)['fio22'], 'Wrong routes count');
                $this->assertCount(1, ($firm->employees)['fio23'], 'Wrong routes count');
            }
            if ($firm->organization == 'test2') {

                $this->assertCount(3, $firm->employees, 'Wrong employee count');
                $this->assertCount(1, ($firm->employees)['fio24'], 'Wrong routes count');
                $this->assertCount(1, ($firm->employees)['fio25'], 'Wrong routes count');
                $this->assertCount(2, ($firm->employees)['fio26'], 'Wrong routes count');
            }

            foreach ($firm->employees as $employee) {
                $this->assertIsArray($employee);
            }
        }

        //в сервисе произошла ошибка
        $fakeRepo = $this->createMock(EducationRouteRepoInterface::class);
        $fakeRepo->method('getRouteReadyPercentForUsersOfFirm')->will($this->throwException(new \Exception()));

        $testedService = new GetRouteReadyPercentForUsersOfFirm($fakeRepo);
        $result = $testedService->getRouteReadyPercentForUsersOfFirm(new percentInputDTO(id: 0, isIssAdmin: false));

        $this->assertIsArray($result, 'Result must have type of Array');
        $this->assertEmpty($result, 'Array must be empty');
    }
}
