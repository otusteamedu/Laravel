<?php

namespace Tests\Feature\Admin\Categories;

use Tests\TestCase;
use App\Models\User;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Services\Commands\CreateCategory\Handler;
use App\Services\Exceptions\Categories\CategorySaveException;
class CategoryCreateTest extends TestCase {
    use RefreshDatabase;

    private User $adminUser;

    protected function setUp(): void
    {
        parent::setUp();

        // Создаем администратора для тестов
        $this->adminUser = User::factory()->create(['is_admin' => true]);
    }

    public function test_admin_can_open_form_create_category()
    {
        $response = $this->actingAs($this->adminUser)
            ->get(route('admin.categories.create'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.categories.create');
    }

    public function test_admin_can_create_new_category()
    {
        $categoryData = [
            'name'        => 'Спорт',
            'color'       => '#00ff00',
            'description' => 'Спортивные задачи'
        ];

        $response = $this->actingAs($this->adminUser)
            ->post(route('admin.categories.store'), $categoryData);

        // Проверяем что нас перенаправили на список категорий
        $response->assertRedirect(route('admin.categories.index'));

        // Проверяем что в базе появилась новая категория
        $this->assertDatabaseHas('categories', $categoryData);

        // Проверяем что показано сообщение об успехе
        $response->assertSessionHas('success', "Категория 'Спорт' успешно создана");
    }

    public function test_cannot_create_category_with_empty_name()
    {
        $categoryData = [
            'name'        => '', // пустое название
            'color'       => '#00ff00',
            'description' => 'Описание'
        ];

        $response = $this->actingAs($this->adminUser)
            ->post(route('admin.categories.store'), $categoryData);

        // Проверяем что нас вернули обратно с ошибкой
        $response->assertSessionHasErrors('name');

        // Проверяем что в базе ничего не создалось
        $this->assertDatabaseMissing('categories', ['description' => 'Описание']);
    }

    public function test_cannot_create_category_with_wrong_color()
    {
        $categoryData = [
            'name'        => 'Тест',
            'color'       => 'красный', // неправильный формат цвета
            'description' => 'Описание'
        ];

        $response = $this->actingAs($this->adminUser)
            ->post(route('admin.categories.store'), $categoryData);

        $response->assertSessionHasErrors('color');
    }

    public function test_cannot_create_category_with_existing_name()
    {
        // Создаем категорию
        Category::factory()->create(['name' => 'Работа']);

        $categoryData = [
            'name'        => 'Работа', // такое название уже есть
            'color'       => '#00ff00',
            'description' => 'Другое описание'
        ];

        $response = $this->actingAs($this->adminUser)
            ->post(route('admin.categories.store'), $categoryData);

        // Проверяем что получили ошибку
        $response->assertSessionHas('error');

        // Проверяем что дубликат не создался
        $this->assertEquals(1, Category::where('name', 'Работа')->count());
    }

    public function test_simple_user_cannot_create_category()
    {
        $simpleUser = User::factory()->create(['is_admin' => false]);

        $response = $this->actingAs($simpleUser)
            ->get(route('admin.categories.create'));

        // Проверяем что доступ запрещен
        $response->assertStatus(403);
    }

    public function test_unauthorized_user_redirected_to_login() {
        $response = $this->get(route('admin.categories.create'));
        $response->assertRedirect(route('login'));
    }

    public function test_handles_category_save_exception()
    {
        // Мок Handler чтобы он выбросил CategorySaveException
        $this->mock(Handler::class, function ($mock) {
            $mock->shouldReceive('handle')
                ->andThrow(new CategorySaveException('Ошибка сохранения'));
        });

        $categoryData = [
            'name' => 'Тестовая категория',
            'color' => '#ff0000',
            'description' => 'Описание'
        ];

        $response = $this->actingAs($this->adminUser)
            ->post(route('admin.categories.store'), $categoryData);

        $response->assertRedirect();
        $response->assertSessionHas('error', 'Ошибка сохранения');
    }

    public function test_handles_unexpected_exception()
    {
        // Мок Handler чтобы он выбросил обычное Exception
        $this->mock(Handler::class, function ($mock) {
            $mock->shouldReceive('handle')
                ->andThrow(new \Exception('Неожиданная ошибка'));
        });

        $categoryData = [
            'name' => 'Тестовая категория',
            'color' => '#ff0000',
            'description' => 'Описание'
        ];

        $response = $this->actingAs($this->adminUser)
            ->post(route('admin.categories.store'), $categoryData);

        $response->assertRedirect();
        $response->assertSessionHas('error', 'Произошла непредвиденная ошибка при создании категории. Попробуйте позже.');
    }
}
