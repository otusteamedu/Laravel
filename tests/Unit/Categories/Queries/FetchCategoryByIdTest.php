<?php
namespace Tests\Unit\Categories\Queries;

use Tests\TestCase;
use App\Services\Queries\FetchCategoryById\Query;
use App\Services\Queries\FetchCategoryById\Fetcher;
use App\Services\DTO\Categories\CategoryDTO;
use App\Models\Category;
use App\Repositories\Categories\CategoryRepositoryInterface;
use App\Services\Exceptions\Categories\CategoryNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;

class FetchCategoryByIdTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_fetch_category_by_id()
    {
        $category = Mockery::mock(Category::class);
        $category->shouldReceive('getAttribute')->with('id')->andReturn(1);
        $category->shouldReceive('getAttribute')->with('name')->andReturn('Работа');
        $category->shouldReceive('getAttribute')->with('color')->andReturn('#ff0000');
        $category->shouldReceive('getAttribute')->with('description')->andReturn('Рабочие задачи');
        $category->shouldReceive('tasks->count')->andReturn(5);

        $repository = Mockery::mock(CategoryRepositoryInterface::class);
        $repository->shouldReceive('find')->with(1)->andReturn($category);

        $fetcher = new Fetcher($repository);
        $query = new Query(1);

        $result = $fetcher->fetch($query);

        $this->assertInstanceOf(CategoryDTO::class, $result);
        $this->assertEquals(1, $result->id);
        $this->assertEquals('Работа', $result->name);
        $this->assertEquals('#ff0000', $result->color);
        $this->assertEquals('Рабочие задачи', $result->description);
        $this->assertEquals(5, $result->tasks_count);
    }

    public function test_throws_exception_if_category_not_found()
    {
        $repository = Mockery::mock(CategoryRepositoryInterface::class);
        $repository->shouldReceive('find')->with(999)->andReturn(null);

        $fetcher = new Fetcher($repository);
        $query = new Query(999);

        $this->expectException(CategoryNotFoundException::class);
        $fetcher->fetch($query);
    }

    public function test_query_stores_id_correctly()
    {
        $query = new Query(42);
        $this->assertEquals(42, $query->id);
    }
}
