<?php
namespace Tests\Unit\Categories\Commands;

use Tests\TestCase;
use App\Services\Commands\CreateCategory\Command;
use App\Services\Commands\CreateCategory\Handler;
use App\Services\Exceptions\Categories\CategoryAlreadyExistsException;
use App\Services\Exceptions\Categories\CategorySaveException;
use App\Repositories\Categories\CategoryRepositoryInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;

class CreateCategoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_category(){
        // создаем мок репозитория
        $repository = Mockery::mock(CategoryRepositoryInterface::class);
        // Говорим мок репозиторию, что категории с таким именем нет
        $repository->shouldReceive('existsByName')->with('Новая категория')->andReturn(false);

        $repository->shouldReceive('save')->andReturn(true);

        $handler = new Handler($repository);

        $command = new Command(
            name: 'Новая категория',
            color: '#ff0000',
            description: 'Описание'
        );

        $result = $handler->handle($command);

        $this->assertTrue($result);
    }

    public function test_throws_exception_if_category_already_exists(){
        // создаем мок репозитория
        $repository = Mockery::mock(CategoryRepositoryInterface::class);

        // Говорим что категория уже есть
        $repository->shouldReceive('existsByName')->with('Существующая')->andReturn(true);

        $handler = new Handler($repository);

        $command = new Command(
            name: 'Существующая',
            color: '#ff0000',
            description: 'Описание'
        );

        // Ожидаем что будет выброшено исключение
        $this->expectException(CategoryAlreadyExistsException::class);
        $this->expectExceptionMessage("Категория с именем 'Существующая' уже существует");

        $handler->handle($command);
    }

    public function test_throws_exception_if_save_fails()
    {
        $repository = Mockery::mock(CategoryRepositoryInterface::class);

        // Категории с таким именем нет
        $repository->shouldReceive('existsByName')->with('Новая категория')->andReturn(false);

        // НО сохранение не удалось (вернуло false)
        $repository->shouldReceive('save')->andReturn(false);

        $handler = new Handler($repository);

        $command = new Command(
            name: 'Новая категория',
            color: '#ff0000',
            description: 'Описание'
        );

        // Ожидаем исключение сохранения
        $this->expectException(CategorySaveException::class);
        $this->expectExceptionMessage("Не удалось сохранить категорию 'Новая категория'");

        $handler->handle($command);
    }
}
