<?php

namespace Tests\Unit\Categories\Repository;

use Tests\TestCase;
use App\Models\Category;
use App\Repositories\Categories\CategoryRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoryRepositoryTest extends TestCase {
    use RefreshDatabase;

    private CategoryRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new CategoryRepository();
    }


    public function test_can_get_all_categories()
    {
        // Создаем 3 категории
        Category::factory()->count(3)->create();

        // Получаем все категории через репозиторий
        $categories = $this->repository->fetchAll();

        // Проверяем, что получили именно 3
        $this->assertCount(3, $categories);
    }

    public function test_can_get_one_category_by_id()
    {
        // Создаем категорию
        $category = Category::factory()->create(['name' => 'Тестовая категория']);
        // Получаем ее через репозиторий
        $found = $this->repository->find($category->id);
        // Проверяем, что получили категорию
        $this->assertNotNull($found);
        $this->assertEquals('Тестовая категория', $found->name);
    }


    public function test_returns_null_if_category_not_found()
    {
        // Пытаемся получить несуществующую категорию
        $found = $this->repository->find(999);
        // Проверяем, что получили null
        $this->assertNull($found);
    }

    public function test_can_save_category()
    {
        // Создаем категорию
        $category = new Category(
            [
                'name'        => 'Новая категория',
                'color'       => '#00ff00',
                'description' => 'Описание'
            ]
        );
        // Сохраняем через репозиторий
        $result = $this->repository->save($category);
        // Проверяем, что сохранение прошло успешно
        $this->assertTrue($result);
        $this->assertDatabaseHas('categories', ['name' => 'Новая категория']);
    }


    public function test_can_check_existence_by_name()
    {
        // Создаем категорию
        Category::factory()->create(['name' => 'Существующая']);
        // Проверяем, что категория существует или не существует
        $exists = $this->repository->existsByName('Существующая');
        $notExists = $this->repository->existsByName('Несуществующая');

        $this->assertTrue($exists);
        $this->assertFalse($notExists);
    }


    public function test_pagination_works_correctly()
    {
        // Создаем 15 категорий
        Category::factory()->count(15)->create();

        // Получаем первые 10
        $firstPage = $this->repository->fetchPaginated(10, 0);
        // Получаем следующие 5
        $secondPage = $this->repository->fetchPaginated(10, 10);
        // Проверяем, что получили правильное количество
        $this->assertCount(10, $firstPage);
        $this->assertCount(5, $secondPage);
    }

    public function test_count_categories_works()
    {
        // Создаем 7 категорий
        Category::factory()->count(7)->create();
        // Проверяем, что получили правильное количество
        $count = $this->repository->count();

        $this->assertEquals(7, $count);
    }

    public function test_can_delete_category()
    {
        // Создаем категорию
        $category = Category::factory()->create();
        // Удаляем через репозиторий
        $result = $this->repository->delete($category);
        // Проверяем, что удаление прошло успешно
        $this->assertTrue($result);
        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }

}
