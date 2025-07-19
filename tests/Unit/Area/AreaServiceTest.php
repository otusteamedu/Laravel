<?php

namespace Tests\Unit;

use App\Exceptions\NotFoundException;
use App\Repositories\Area\AreaDTO;
use App\Repositories\Area\AreaRepository;
use App\Repositories\Area\AreaRepositoryInterface;
use App\Services\Area\AreaService;
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
        $data = array_map(fn ($class) => Mockery::mock(AreaDTO::class), $mockedData);
        $this->repository->shouldReceive('getAll')
            ->once()
            ->andReturn($data);
        if ($expectException) {
            $this->expectException(NotFoundException::class);
            $this->expectExceptionMessage($expectedMessage);
        }
        $result = $this->service->prepairDataForIndex();
        if (!$expectException) {
            $this->assertSame($data, $result);
        }
    }

    #[Test]
    public function store_delegates_to_repository_with_correct_name(): void
    {
        $name = 'Новая территория';
        $this->repository->shouldReceive('store')
            ->once()
            ->with($name);
        $this->service->store($name);
    }

    #[Test]
    #[TestWith([42])]
    public function prepairDataForEdit_returns_area_dto_from_repository(
        int $id,
    ): void {
        $areaDtoMock = Mockery::mock(AreaDTO::class);
        $this->repository->shouldReceive('findById')
            ->once()
            ->with($id)
            ->andReturn($areaDtoMock);
        $result = $this->service->prepairDataForEdit($id);
        $this->assertSame($areaDtoMock, $result);
    }

    #[Test]
    #[TestWith([42, 'Новое имя'])]
    public function update_updates_area_name_and_calls_repository(
        int $id,
        string $newName,
    ): void {
        $areaDtoMock = Mockery::mock(AreaDTO::class);
        $areaDtoMock->shouldReceive('name')
            ->andReturnUsing(function () use (&$newName) {
                return $newName;
            });
        $areaDtoMock->name = null;
        $this->repository->shouldReceive('findById')
            ->once()
            ->with($id)
            ->andReturn($areaDtoMock);
        $this->repository->shouldReceive('update')
            ->once()
            ->with(Mockery::on(function ($dto) use ($areaDtoMock, $newName) {
                return $dto === $areaDtoMock && $dto->name === $newName;
            }));
        $this->service->update($id, $newName);
        $this->assertSame($newName, $areaDtoMock->name);
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
