<?php

namespace App\Modules\ISS\tests\Unit\issServices;

//use PHPUnit\Framework\TestCase;
use App\Modules\ISS\tests\TestCase;
use Mockery\MockInterface;
use App\Modules\ISS\src\Repositories\EducationRoutePointRepo;
use App\Modules\ISS\src\Services\EducationRoutePoint\getFilesOfRealPointData\GetFilesOfRealPointData;
use App\Modules\ISS\src\Services\EducationRoutePoint\getFilesOfRealPointData\InputDTO as filesInputDTO;
use App\Modules\ISS\src\Services\EducationRoutePoint\getFilesOfRealPointData\OutputDTO as filesOutputDTO;
use App\Modules\ISS\src\Services\EducationRoutePoint\getRealPointMainData\GetRealPointMainData;
use App\Modules\ISS\src\Services\EducationRoutePoint\getRealPointMainData\InputDTO as mainDataInputDTO;
use App\Modules\ISS\src\Services\EducationRoutePoint\getRealPointMainData\OutputDTO as mainDataOutputDTO;

class EducationRoutePointTest extends TestCase
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
     * Проверка что сервис возвращает правильную структуру данных для файлов учебных материалов
     * для заданной точки учедного маршрута
     */
    #[Group(name: "getFilesOfRealPointData")]
    public function test_get_files_of_real_point_data_service()
    {
        //сервис отработал правильно
        $fakeRepo = $this->mock(EducationRoutePointRepo::class, function (MockInterface $mock) {
            $mock->shouldReceive('getFilesOfRealPointData')->times(3)
                ->andReturn(
                    [
                        ['title' =>'example1', 'file_path' => 'example\file\text\1'],
                        ['title' =>'example2', 'file_path' => 'example\file\text\2'],
                    ]
                );
        });

        $testedService = new GetFilesOfRealPointData($fakeRepo);
        $result = $testedService->getFilesOfRealPointData(new filesInputDTO(id: 0));

        $this->assertInstanceOf(filesOutputDTO::class, $result, 'Wrong result type');
        $this->assertIsArray($result->materials, 'Materials must be 2 dimension array');
        $this->assertCount(
            count(config('iss.ALLOWED_EDUCATION_MATERIAL_TYPES')),
            $result->materials, 'Wrong material types count'
        );
        foreach (config('iss.ALLOWED_EDUCATION_MATERIAL_TYPES') as $matType) {
            $this->assertArrayHasKey($matType, $result->materials, 'Material type ' . $matType . ' missing');
        }
        foreach ($result->materials as $material) {
            $this->assertCount(2, $material, 'Missing one of material file');
        }

        //в сервисе возникли ошибки
        $fakeRepo = $this->createMock(EducationRoutePointRepo::class);
        $fakeRepo->method('getFilesOfRealPointData')->will($this->throwException(new \Exception()));

        $testedService = new GetFilesOfRealPointData($fakeRepo);
        $result = $testedService->getFilesOfRealPointData(new filesInputDTO(id: 0));

        $this->assertNull($result, 'If error should return null');
    }

    /**
     * Проверка что сервис возвращает правильную структуру данных для
     * основных свойств заданной точки обучающего марзрута
     */
    #[Group(name: "getRealPointMainData")]
    public function test_get_real_point_main_data_service()
    {
        //сервис отработал без ошибок
        $fakeRepo = $this->mock(EducationRoutePointRepo::class, function (MockInterface $mock) {
            $mock->shouldReceive('getRealPointMainData')->times(1)
                ->andReturn(
                    [
                        'route_point_id'=> 806,
                        'exam_date'=> '11-07-09',
                        'route_name'=> 'rTest',
                        'point_name'=> 'test',
                        'last_passed_exam_date'=> '03-11-27',
                        'exam_result'=> 'passed',
                    ],
                );
        });

        $testedService = new GetRealPointMainData($fakeRepo);
        $result = $testedService->getRealPointMainData(new mainDataInputDTO(id: 0, userDataId: 0));

        $this->assertInstanceOf(mainDataOutputDTO::class, $result, 'Wrong result type');
        $this->assertNotNull($result->routeName, 'RouteName missing');
        $this->assertNotNull($result->routePointId, 'RoutePointID missing');
        $this->assertNotNull($result->examResult, 'ExamResult missing');
        $this->assertNotNull($result->examDate, 'ExamDate missing');
        $this->assertNotNull($result->pointName, 'PointName missing');
        $this->assertNotNull($result->lastPassedExamDate, 'LastPassedExamDate missing');

        //сервис отработал нормально но ничего не нашел
        $fakeRepo = $this->mock(EducationRoutePointRepo::class, function (MockInterface $mock) {
            $mock->shouldReceive('getRealPointMainData')->times(1)->andReturn([]);
        });

        $testedService = new GetRealPointMainData($fakeRepo);
        $result = $testedService->getRealPointMainData(new mainDataInputDTO(id: 0, userDataId: 0));

        $this->assertNull($result, 'No data found return null');

        //в сервисе возникли ошибки
        $fakeRepo = $this->createMock(EducationRoutePointRepo::class);
        $fakeRepo->method('getRealPointMainData')->will($this->throwException(new \Exception()));

        $testedService = new GetRealPointMainData($fakeRepo);
        $result = $testedService->getRealPointMainData(new mainDataInputDTO(id: 0, userDataId: 0));

        $this->assertNull($result, 'If any errors return null');
    }
}
