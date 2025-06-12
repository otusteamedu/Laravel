<?php

declare(strict_types=1);

namespace Tests\Unit\Services\Category\Handlers;

use App\Models\Category;
use App\Services\Category\Commands\CommandDTO;
use App\Services\Category\Exceptions\CategoryNotFoundException;
use App\Services\Category\Handlers\UpdateHandler;
use App\Services\Category\Repositories\CategoryRepositoryInterface;
use App\Services\Category\Results\CategoryDTO;
use Mockery;
use PHPUnit\Framework\Attributes\Group;
use Tests\TestCase;

#[Group('category-handlers')]
class UpdateHandlerTest extends TestCase
{
    private CategoryRepositoryInterface $repository;
    private UpdateHandler $handler;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = Mockery::mock(CategoryRepositoryInterface::class);
        $this->handler = new UpdateHandler($this->repository);
    }

    public function test_it_updates_category_successfully(): void
    {
        // Arrange
        $category = new Category();
        $category->id = 1;
        $category->name = 'Old Name';
        $category->slug = 'old-name';
        $category->sort = 1;

        $dto = new CommandDTO(
            name: 'Updated Name',
            sort: 200,
            id: 1
        );

        $this->repository
            ->shouldReceive('find')
            ->with(1)
            ->once()
            ->andReturn($category);

        $this->repository
            ->shouldReceive('save')
            ->once()
            ->with(Mockery::on(function ($savedCategory) use ($dto) {
                return $savedCategory->name === $dto->name
                       && $savedCategory->sort === $dto->sort;
            }))
            ->andReturn(true);

        // Act
        $result = ($this->handler)($dto);

        // Assert
        $this->assertInstanceOf(CategoryDTO::class, $result);
        $this->assertEquals(1, $result->id);
        $this->assertEquals('Updated Name', $result->name);
        $this->assertEquals('old-name', $result->slug);
        $this->assertEquals(200, $result->sort);
    }

    public function test_it_throws_exception_when_category_not_found(): void
    {
        // Arrange
        $dto = new CommandDTO(
            name: 'Test Category',
            sort: 100,
            id: 999
        );

        $this->repository
            ->shouldReceive('find')
            ->with(999)
            ->once()
            ->andReturn(null);

        // Assert & Act
        $this->expectException(CategoryNotFoundException::class);
        $this->expectExceptionMessage('Category not found');

        ($this->handler)($dto);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        Mockery::close();
    }
}
