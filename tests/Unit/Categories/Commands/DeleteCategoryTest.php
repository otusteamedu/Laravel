<?php
namespace Tests\Unit\Categories\Commands;

use Tests\TestCase;
use App\Services\Commands\DeleteCategory\Command;
use App\Services\Commands\DeleteCategory\Handler;
use App\Services\Exceptions\Categories\CategoryNotFoundException;
use App\Services\Exceptions\Categories\CategoryHasTasksException;
use App\Repositories\Categories\CategoryRepositoryInterface;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;

class DeleteCategoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_delete_empty_category()
    {
        $category = Mockery::mock(Category::class);
        $category->shouldReceive('tasks->count')->andReturn(0);
        
        $repository = Mockery::mock(CategoryRepositoryInterface::class);
        $repository->shouldReceive('find')->with(1)->andReturn($category);
        $repository->shouldReceive('delete')->with($category)->andReturn(true);

        $handler = new Handler($repository);
        $command = new Command(1);

        $result = $handler->handle($command);
        $this->assertTrue($result);
    }

    public function test_throws_exception_if_category_not_found()
    {
        $repository = Mockery::mock(CategoryRepositoryInterface::class);
        $repository->shouldReceive('find')->with(999)->andReturn(null);

        $handler = new Handler($repository);
        $command = new Command(999);

        $this->expectException(CategoryNotFoundException::class);
        $handler->handle($command);
    }

    public function test_throws_exception_if_category_has_tasks()
    {
        $category = Mockery::mock(Category::class);
        $category->shouldReceive('getAttribute')->with('name')->andReturn('Категория с задачами');
        $category->shouldReceive('tasks->count')->andReturn(5);
        
        $repository = Mockery::mock(CategoryRepositoryInterface::class);
        $repository->shouldReceive('find')->with(1)->andReturn($category);

        $handler = new Handler($repository);
        $command = new Command(1);

        $this->expectException(CategoryHasTasksException::class);
        $this->expectExceptionMessage("Нельзя удалить категорию 'Категория с задачами', в ней есть задачи");
        
        $handler->handle($command);
    }
}
