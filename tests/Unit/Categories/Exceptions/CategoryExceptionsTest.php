<?php
namespace Tests\Unit\Categories\Exceptions;

use App\Services\Exceptions\Categories\CategoryHasTasksException;
use Tests\TestCase;
use App\Services\Exceptions\Categories\CategoryAlreadyExistsException;
use App\Services\Exceptions\Categories\CategoryNotFoundException;
use App\Services\Exceptions\Categories\CategorySaveException;

class CategoryExceptionsTest extends TestCase
{
    public function test_exception_already_exists()
    {
        $exception = new CategoryAlreadyExistsException('Работа');

        $this->assertEquals("Категория с именем 'Работа' уже существует", $exception->getMessage());
    }

    public function test_exception_category_not_found()
    {
        $exception = new CategoryNotFoundException();

        $this->assertInstanceOf(\Exception::class, $exception);
    }

    public function test_exception_category_save()
    {
        $exception = new CategorySaveException("Не удалось сохранить категорию 'Тест'");

        $this->assertEquals("Не удалось сохранить категорию 'Тест'", $exception->getMessage());
    }

    public function test_exception_category_has_tasks(){
        $exception = new CategoryHasTasksException('Работа');

        $this->assertInstanceOf(\Exception::class, $exception);
        $this->assertEquals("Нельзя удалить категорию 'Работа', в ней есть задачи", $exception->getMessage());
    }
}
