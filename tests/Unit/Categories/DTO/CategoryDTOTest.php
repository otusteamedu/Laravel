<?php
namespace Tests\Unit\Categories\DTO;

use Tests\TestCase;
use App\Services\DTO\Categories\CategoryDTO;

class CategoryDTOTest extends TestCase
{
    public function test_can_create_category_dto()
    {
        $dto = new CategoryDTO(
            id: 1,
            name: 'Работа',
            color: '#ff0000',
            description: 'Рабочие задачи',
            tasks_count: 5
        );

        $this->assertEquals(1, $dto->id);
        $this->assertEquals('Работа', $dto->name);
        $this->assertEquals('#ff0000', $dto->color);
        $this->assertEquals('Рабочие задачи', $dto->description);
        $this->assertEquals(5, $dto->tasks_count);
    }

    public function test_dto_is_readonly()
    {
        $dto = new CategoryDTO(1, 'Тест', '#000000', 'Описание', 0);
        
        // Проверяем что DTO readonly - нельзя изменить свойства
        $this->assertTrue(property_exists($dto, 'id'));
        $this->assertTrue(property_exists($dto, 'name'));
        $this->assertTrue(property_exists($dto, 'color'));
        $this->assertTrue(property_exists($dto, 'description'));
        $this->assertTrue(property_exists($dto, 'tasks_count'));
    }

    public function test_can_create_dto_with_null_description()
    {
        $dto = new CategoryDTO(
            id: 1,
            name: 'Категория',
            color: '#ff0000',
            description: null,
            tasks_count: 0
        );

        $this->assertNull($dto->description);
    }

    public function test_can_create_dto_with_zero_tasks()
    {
        $dto = new CategoryDTO(
            id: 1,
            name: 'Пустая категория',
            color: '#ff0000',
            description: 'Без задач',
            tasks_count: 0
        );

        $this->assertEquals(0, $dto->tasks_count);
    }
}
