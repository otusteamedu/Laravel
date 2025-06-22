<?php
namespace Tests\Unit\Categories\Commands;

use Tests\TestCase;
use App\Services\Commands\UpdateCategory\Command;
use App\Services\Commands\UpdateCategory\Handler;
use App\Services\Exceptions\Categories\CategoryAlreadyExistsException;
use App\Services\Exceptions\Categories\CategoryNotFoundException;
use App\Repositories\Categories\CategoryRepositoryInterface;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Services\DTO\Categories\CategoryDTO;
use Mockery;

class UpdateCategoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_update_category()
    {
        $category = Mockery::mock(Category::class);
        // Первый вызов name для проверки изменения
        $category->shouldReceive('getAttribute')->with('name')->andReturn('Старое название')->once();

        // Установка новых значений
        $category->shouldReceive('setAttribute')->with('name', 'Новое название');
        $category->shouldReceive('setAttribute')->with('color', '#00ff00');
        $category->shouldReceive('setAttribute')->with('description', 'Новое описание');

        // Вызовы для создания DTO после изменения
        $category->shouldReceive('getAttribute')->with('id')->andReturn(1);
        $category->shouldReceive('getAttribute')->with('name')->andReturn('Новое название');
        $category->shouldReceive('getAttribute')->with('color')->andReturn('#00ff00');
        $category->shouldReceive('getAttribute')->with('description')->andReturn('Новое описание');
        $category->shouldReceive('tasks->count')->andReturn(5);

        $repository = Mockery::mock(CategoryRepositoryInterface::class);
        $repository->shouldReceive('find')->with(1)->andReturn($category);
        $repository->shouldReceive('existsByName')->with('Новое название')->andReturn(false);
        $repository->shouldReceive('save')->andReturn(true);

        $handler = new Handler($repository);

        $command = new Command(
            id: 1,
            name: 'Новое название',
            color: '#00ff00',
            description: 'Новое описание'
        );

        $result = $handler->handle($command);

        $this->assertInstanceOf(\App\Services\DTO\Categories\CategoryDTO::class, $result);
        $this->assertEquals(1, $result->id);
        $this->assertEquals('Новое название', $result->name);
    }

    public function test_throws_exception_if_category_not_found()
    {
        $repository = Mockery::mock(CategoryRepositoryInterface::class);
        $repository->shouldReceive('find')->with(999)->andReturn(null);

        $handler = new Handler($repository);

        $command = new Command(
            id: 999,
            name: 'Название',
            color: '#ff0000',
            description: 'Описание'
        );

        $this->expectException(CategoryNotFoundException::class);
        $handler->handle($command);
    }

    public function test_throws_exception_if_new_name_already_exists()
    {
        $category = Mockery::mock(Category::class);
        $category->shouldReceive('getAttribute')->with('name')->andReturn('Старое название');

        $repository = Mockery::mock(CategoryRepositoryInterface::class);
        $repository->shouldReceive('find')->with(1)->andReturn($category);
        $repository->shouldReceive('existsByName')->with('Занятое название')->andReturn(true);

        $handler = new Handler($repository);

        $command = new Command(
            id: 1,
            name: 'Занятое название',
            color: '#ff0000',
            description: 'Описание'
        );

        $this->expectException(CategoryAlreadyExistsException::class);
        $handler->handle($command);
    }

    public function test_can_keep_same_name()
    {
        $category = Mockery::mock(Category::class);
        $category->shouldReceive('getAttribute')->with('name')->andReturn('Название');
        $category->shouldReceive('setAttribute')->with('name', 'Название');
        $category->shouldReceive('setAttribute')->with('color', '#00ff00');
        $category->shouldReceive('setAttribute')->with('description', 'Новое описание');
        $category->shouldReceive('getAttribute')->with('id')->andReturn(1);
        $category->shouldReceive('getAttribute')->with('name')->andReturn('Название');
        $category->shouldReceive('getAttribute')->with('color')->andReturn('#00ff00');
        $category->shouldReceive('getAttribute')->with('description')->andReturn('Новое описание');
        $category->shouldReceive('tasks->count')->andReturn(0);

        $repository = Mockery::mock(CategoryRepositoryInterface::class);
        $repository->shouldReceive('find')->with(1)->andReturn($category);
        $repository->shouldReceive('save')->andReturn(true);

        $handler = new Handler($repository);

        $command = new Command(
            id: 1,
            name: 'Название', // то же название
            color: '#00ff00',
            description: 'Новое описание'
        );

        $result = $handler->handle($command);
        $this->assertInstanceOf(CategoryDTO::class, $result);
        $this->assertEquals(1, $result->id);
        $this->assertEquals('Название', $result->name);
    }
}
