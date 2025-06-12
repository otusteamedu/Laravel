<?php

declare(strict_types=1);

namespace Tests\Unit\Services\Category\Handlers;

use App\Models\Category;
use App\Services\Category\Exceptions\CategoryNotFoundException;
use App\Services\Category\Handlers\DestroyHandler;
use App\Services\Category\Repositories\CategoryRepositoryInterface;
use Mockery;
use PHPUnit\Framework\Attributes\Group;
use Tests\TestCase;

#[Group('category-handlers')]
class DestroyHandlerTest extends TestCase
{
    private CategoryRepositoryInterface $repository;
    private DestroyHandler $handler;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = Mockery::mock(CategoryRepositoryInterface::class);
        $this->handler = new DestroyHandler($this->repository);
    }

    public function test_it_deletes_category_successfully(): void
    {
        // Arrange
        $category = Mockery::mock(Category::class);

        $this->repository
            ->shouldReceive('find')
            ->with(1)
            ->once()
            ->andReturn($category);

        $this->repository
            ->shouldReceive('delete')
            ->with($category)
            ->once()
            ->andReturn(true);

        // Act
        $result = ($this->handler)(1);

        // Assert
        $this->assertTrue($result);
    }

    public function test_it_throws_exception_when_category_not_found(): void
    {
        // Arrange
        $this->repository
            ->shouldReceive('find')
            ->with(999)
            ->once()
            ->andReturn(null);

        // Assert & Act
        $this->expectException(CategoryNotFoundException::class);
        $this->expectExceptionMessage('Category not found');

        ($this->handler)(999);
    }

    public function test_it_returns_false_when_delete_fails(): void
    {
        // Arrange
        $category = Mockery::mock(Category::class);

        $this->repository
            ->shouldReceive('find')
            ->with(1)
            ->once()
            ->andReturn($category);

        $this->repository
            ->shouldReceive('delete')
            ->with($category)
            ->once()
            ->andReturn(false);

        // Act
        $result = ($this->handler)(1);

        // Assert
        $this->assertFalse($result);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        Mockery::close();
    }
}
