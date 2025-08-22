<?php

namespace Tests\Feature\Area;

use App\Domain\BusinessModels\Area;
use App\Infrastructure\Repositories\Area\AreaRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestWith;
use Tests\TestCase;
use App\Infrastructure\Helpers\LocaleHelper;
use App\Domain\Factories\Area\AreaFactory;
use App\Domain\ValueObjects\Area\AreaName;

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
        foreach ($areas as $area) {
            $this->assertInstanceOf(Area::class, $area);
        }
    }

    #[Test]
    #[TestWith(['Тестовый участок'])]
    public function store_creates_area_with_current_locale_field(
        string $name,
    ): void {
        $lang = LocaleHelper::getLocale();
        $area = AreaFactory::make($name, $lang);
        $this->repository->store($area);
        $this->assertDatabaseHas('areas', $area->toArray());
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
            $this->assertInstanceOf(Area::class, $result);
            $this->assertEquals($id, $result->getId());
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
        $newName = new AreaName($newName);
        $area->rename($newName);
        $this->repository->update($area);
        if (!$shouldThrow) {
            $updatedArea = $this->repository->findById($id);
            $this->assertEquals($newName, $updatedArea->getName());
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
