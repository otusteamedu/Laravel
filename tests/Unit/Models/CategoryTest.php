<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Category;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoryTest extends TestCase {
    use RefreshDatabase;


    public function test_category_can_be_created()
    {
        //Создаем категорию
        $category = Category::create(
            [
                'name'        => 'Работа',
                'color'       => '#ff0000',
                'description' => 'Рабочие задачи'
            ]
        );

        // Проверяем, что категория создалась правильно
        $this->assertEquals('Работа', $category->name);
        $this->assertEquals('#ff0000', $category->color);
        $this->assertEquals('Рабочие задачи', $category->description);
    }


    public function test_category_is_related_to_tasks()
    {
        // Создаем категорию
        $category = Category::factory()->create();

        // Создаем задачи для этой категории
        $tasks = Task::factory()->count(3)->create(
            [
                'category_id' => $category->id
            ]
        );

        // Проверяем, что у категории есть эти задачи
        $this->assertCount(3, $category->tasks);
    }

    public function test_can_get_the_number_of_tasks_in_a_category()
    {
        $category = Category::factory()->create();
        // Создаем задачи в этой категории
        Task::factory()->count(5)->create(['category_id' => $category->id]);
        // Проверяем, что у категории есть эти задачи
        $this->assertEquals(5, $category->tasks()->count());
    }

}
