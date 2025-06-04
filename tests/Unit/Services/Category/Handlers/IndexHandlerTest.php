<?php

declare(strict_types=1);

namespace Tests\Unit\Services\Category\Handlers;

use App\Models\Category;
use App\Services\Category\Handlers\IndexHandler;
use App\Services\Category\Repositories\CategoryRepositoryInterface;
use App\Services\Category\Results\CategoryDTO;
use App\Services\Category\Results\Fetcher;
use App\Services\Category\Results\CategoriesDTO;
use Mockery;
use PHPUnit\Framework\Attributes\Group;
use Tests\TestCase;

#[Group('category-handlers')]
class IndexHandlerTest extends TestCase
{
    private CategoryRepositoryInterface $repository;
    private Fetcher $fetcher;
    private IndexHandler $handler;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = Mockery::mock(CategoryRepositoryInterface::class);
        $this->fetcher = Mockery::mock(Fetcher::class);
        $this->handler = new IndexHandler($this->repository, $this->fetcher);
    }

    public function test_it_returns_categories_when_found(): void
    {
        // Arrange
        $categories = [
            $this->createCategory(1, 'First Category', 'first-category', 100),
            $this->createCategory(2, 'Second Category', 'second-category', 200),
        ];

        $dtos = [
            new CategoryDTO(1, 'First Category', 'first-category', 100),
            new CategoryDTO(2, 'Second Category', 'second-category', 200),
        ];

        $result = new CategoriesDTO($dtos);

        $this->repository
            ->shouldReceive('fetchAll')
            ->once()
            ->andReturn($categories);

        $this->fetcher
            ->shouldReceive('fetch')
            ->once()
            ->with($categories)
            ->andReturn($result);

        // Act
        $result = ($this->handler)();

        // Assert
        $this->assertInstanceOf(CategoriesDTO::class, $result);
        $this->assertCount(2, $result->results);
        $this->assertContainsOnlyInstancesOf(CategoryDTO::class, $result->results);

        $this->assertEquals(1, $result->results[0]->id);
        $this->assertEquals('First Category', $result->results[0]->name);
        $this->assertEquals('first-category', $result->results[0]->slug);
        $this->assertEquals(100, $result->results[0]->sort);

        $this->assertEquals(2, $result->results[1]->id);
        $this->assertEquals('Second Category', $result->results[1]->name);
        $this->assertEquals('second-category', $result->results[1]->slug);
        $this->assertEquals(200, $result->results[1]->sort);
    }

    public function test_it_returns_empty_result_when_no_categories(): void
    {
        // Arrange
        $this->repository
            ->shouldReceive('fetchAll')
            ->once()
            ->andReturn([]);

        $this->fetcher
            ->shouldReceive('fetch')
            ->once()
            ->with([])
            ->andReturn(new CategoriesDTO([]));

        // Act
        $result = ($this->handler)();

        // Assert
        $this->assertInstanceOf(CategoriesDTO::class, $result);
        $this->assertEmpty($result->results);
    }

    private function createCategory(int $id, string $name, string $slug, int $sort): Category
    {
        $category = new Category();
        $category->id = $id;
        $category->name = $name;
        $category->slug = $slug;
        $category->sort = $sort;
        return $category;
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        Mockery::close();
    }
}
