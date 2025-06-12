<?php

declare(strict_types=1);

namespace Tests\Unit\Services\Category\Handlers;

use App\Models\Category;
use App\Services\Category\Exceptions\CategoryNotFoundException;
use App\Services\Category\Handlers\ShowHandler;
use App\Services\Category\Repositories\CategoryRepositoryInterface;
use App\Services\Category\Results\CategoryDTO;
use Mockery;
use PHPUnit\Framework\Attributes\Group;
use Tests\TestCase;

#[Group('category-handlers')]
class ShowHandlerTest extends TestCase
{
    private CategoryRepositoryInterface $repository;
    private ShowHandler $handler;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = Mockery::mock(CategoryRepositoryInterface::class);
        $this->handler = new ShowHandler($this->repository);
    }

    public function test_it_returns_category_dto_when_found(): void
    {
        // Arrange
        $category = new Category();
        $category->id = 1;
        $category->name = 'Test Category';
        $category->slug = 'test-category';
        $category->sort = 100;

        $this->repository
            ->shouldReceive('find')
            ->with(1)
            ->once()
            ->andReturn($category);

        // Act
        $result = ($this->handler)(1);

        // Assert
        $this->assertInstanceOf(CategoryDTO::class, $result);
        $this->assertEquals(1, $result->id);
        $this->assertEquals('Test Category', $result->name);
        $this->assertEquals('test-category', $result->slug);
        $this->assertEquals(100, $result->sort);
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

    protected function tearDown(): void
    {
        parent::tearDown();
        Mockery::close();
    }
} 