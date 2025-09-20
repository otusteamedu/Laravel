<?php

namespace Tests\Unit\Area;

use App\Application\Exceptions\NotFoundServiceException;
use App\Infrastructure\Helpers\LocaleHelper;
use App\Domain\BusinessModels\Area;
use App\Application\Services\Area\AreaDTO;
use App\Application\Services\Area\AreaRepositoryInterface;
use App\Application\Services\Area\AreaService;
use App\Domain\ValueObjects\Lang;
use App\Domain\ValueObjects\Area\AreaName;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestWith;
use Tests\TestCase;

#[Group('unit_area_service')]

class AreaServiceTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    private $repository;
    private AreaService $service;

    public function setUp(): void
    {
        parent::setUp();
        $this->repository = Mockery::mock(AreaRepositoryInterface::class);
        $this->service = new AreaService($this->repository);
    }

    #[Test]
    #[TestWith([[Mockery::class, Mockery::class], false, null])]
    #[TestWith([[], true, 'Записи отсутствуют.'])]
    public function prepairDataForIndex_handles_data_or_exception(
        array $mockedData,
        bool $expectException,
        ?string $expectedMessage
    ): void {
        $data = array_map(function () {
            $area = Mockery::mock(Area::class);
            $area->shouldReceive('getId')->andReturn(1);
            $area->shouldReceive('getName')->andReturn(new AreaName('Тестовая территория'));
            $area->shouldReceive('getCreatedAt')->andReturn('2000-01-01');
            return $area;
        }, $mockedData);
        $this->repository->shouldReceive('getAll')
            ->once()
            ->andReturn($data);
        if ($expectException) {
            $this->expectException(NotFoundServiceException::class);
            $this->expectExceptionMessage($expectedMessage);
        }
        $result = $this->service->prepairDataForIndex();
        if (!$expectException) {
            $this->assertIsArray($result);
            $this->assertContainsOnlyInstancesOf(AreaDTO::class, $result);
        }
    }

    #[Test]
    public function store_delegates_to_repository_with_correct_name(): void
    {
        $name = 'Новая территория';
        $lang = LocaleHelper::getLocale();
        $expectedArea = new Area(
            new AreaName($name),
            new Lang($lang),
        );
        $this->repository->shouldReceive('store')
            ->once()
            ->with(Mockery::on(function ($area) use ($expectedArea) {
                return $area instanceof Area
                    && $area->getName()->getValue() === $expectedArea->getName()->getValue()
                    && $area->getLang()->getValue() === $expectedArea->getLang()->getValue();
            }));
        $this->service->store($name, $lang);
    }

    #[Test]
    #[TestWith([42])]
    public function prepairDataForEdit_returns_area_dto_from_repository(
        int $id,
    ): void {
        $name = 'Новая территория';
        $lang = LocaleHelper::getLocale();
        $areaMock = \Mockery::mock(Area::class);
        $areaMock->shouldReceive('getId')->andReturn($id);
        $areaMock->shouldReceive('getName')->andReturn(new AreaName($name));
        $areaMock->shouldReceive('getLang')->andReturn(new Lang($lang));
        $areaMock->shouldReceive('getCreatedAt')->andReturn('2025-01-01 12:00:00');
        $this->repository->shouldReceive('findById')
            ->once()
            ->with($id)
            ->andReturn($areaMock);
        $result = $this->service->prepairDataForEdit($id);
        $this->assertInstanceOf(AreaDTO::class, $result);
        $this->assertSame($name, $result->name);
    }

    #[Test]
    #[TestWith([42, 'Новое имя'])]
    public function update_updates_area_name_and_calls_repository(
        int $id,
        string $newName,
    ): void {
        $areaMock = Mockery::mock(Area::class);
        $areaMock->shouldReceive('rename')
            ->once()
            ->with(Mockery::on(fn ($arg) => $arg instanceof AreaName && $arg->getValue() === $newName));
        $this->repository->shouldReceive('findById')
            ->once()
            ->with($id)
            ->andReturn($areaMock);
        $this->repository->shouldReceive('update')
            ->once()
            ->with($areaMock);
        $this->service->update($id, $newName);
    }

    #[Test]
    #[TestWith([42])]
    public function delete_calls_repository_delete_with_correct_id(
        int $id
    ): void {
        $this->repository->shouldReceive('delete')
            ->once()
            ->with($id);
        $this->service->delete($id);
    }
    
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
