<?php

namespace Tests\Feature\Feature\Area;

use App\Models\Area;
use App\Repositories\Area\AreaDTO;
use App\Repositories\Area\AreaRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestWith;
use Tests\TestCase;

#[Group('feature_area_repository')]

class AreaRepositoryTest extends TestCase
{
    use DatabaseTransactions;

    private AreaRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new AreaRepository();
    }

    #[Test]
    public function test_getAll_returns_non_empty_array_of_AreaDTOs(): void
    {
        $areas = $this->repository->getAll();
        $this->assertIsArray($areas);
        $this->assertNotEmpty($areas, 'Ожидается, что база содержит записи');
        foreach ($areas as $areaDTO) {
            $this->assertInstanceOf(AreaDTO::class, $areaDTO);
        }
    }

    #[Test]
    #[TestWith(['Тестовый участок'])]
    public function store_creates_area_with_current_locale_field(
        string $name,
    ): void {
        app()->setLocale('ru');
        $this->repository->store($name);
        $this->assertDatabaseHas('areas', [
            'name_ru' => $name,
        ]);
    }

    #[Test]
    #[TestWith([1, false])]
    #[TestWith([999999, true])]
    public function find_by_id_behaves_correctly(
        int $id, 
        bool $shouldThrow
    ): void {
        if ($shouldThrow) {
            $this->expectException(ModelNotFoundException::class);
        }
        $result = $this->repository->findById($id);
        if (!$shouldThrow) {
            $this->assertInstanceOf(AreaDTO::class, $result);
            $this->assertEquals($id, $result->id);
        }
    }

    #[Test]
    #[TestWith([1, 'Новое имя', false])]
    #[TestWith([999999, 'Неважно', true])]
    public function update_updates_or_fails(
        int $id, 
        string $newName,
        bool $shouldThrow
    ): void {
        if ($shouldThrow) {
            $this->expectException(ModelNotFoundException::class);
        }
        $area = $this->repository->findById($id);
        $area->name = $newName;
        $this->repository->update($area);
        if (!$shouldThrow) {
            $updatedArea = $this->repository->findById($id);
            $this->assertEquals($newName, $updatedArea->name);
        }
    }

    #[Test]
    #[TestWith([1, false])]
    #[TestWith([999999, true])]
    public function delete_deletes_or_throws(
        int $id,
        bool $shouldThrow
    ): void {
        if ($shouldThrow) {
            $this->expectException(ModelNotFoundException::class);
        }
        $this->repository->delete($id);
        if (!$shouldThrow) {
            $this->assertDatabaseMissing('areas', ['id' => $id]);
        }
    }
}
