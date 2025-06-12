<?php

declare(strict_types=1);

namespace Tests\Unit\Services\Category\Handlers;

use App\Models\Category;
use App\Services\Category\Commands\CommandDTO;
use App\Services\Category\Handlers\CreateHandler;
use App\Services\Category\Repositories\CategoryRepositoryInterface;
use Mockery;
use PHPUnit\Framework\Attributes\Group;
use Tests\TestCase;

#[Group('category-handlers')]
class CreateHandlerTest extends TestCase
{
    private CategoryRepositoryInterface $repository;
    private CreateHandler $handler;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = Mockery::mock(CategoryRepositoryInterface::class);
        $this->handler = new CreateHandler($this->repository);
    }

    public function test_it_creates_category_successfully(): void
    {
        // Arrange
        $category = new Category();
        $dto = new CommandDTO(
            name: 'Test Category',
            sort: 100
        );

        $this->repository
            ->shouldReceive('create')
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
        $this->assertTrue($result);
    }

    public function test_it_handles_save_failure(): void
    {
        // Arrange
        $category = new Category();
        $dto = new CommandDTO(
            name: 'Test Category',
            sort: 100
        );

        $this->repository
            ->shouldReceive('create')
            ->once()
            ->andReturn($category);

        $this->repository
            ->shouldReceive('save')
            ->once()
            ->andReturn(false);

        // Act
        $result = ($this->handler)($dto);

        // Assert
        $this->assertFalse($result);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        Mockery::close();
    }
}