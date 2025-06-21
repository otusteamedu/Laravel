<?php

namespace Tests\Feature\Admin\Categories;

use Tests\TestCase;
use App\Models\User;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Services\Commands\UpdateCategory\Handler;
use App\Services\Exceptions\Categories\CategoryAlreadyExistsException;
class CategoryUpdateTest extends TestCase {
    use RefreshDatabase;

    private User $adminUser;
    private Category $category;

    protected function setUp(): void
    {
        parent::setUp();
        $this->adminUser = User::factory()->create(['is_admin' => true]);
        $this->category  = Category::factory()->create(
            [
                'name'        => 'Старое название',
                'color'       => '#ff0000',
                'description' => 'Старое описание'
            ]
        );
    }

    public function test_admin_can_open_edit_form()
    {
        $response = $this->actingAs($this->adminUser)
            ->get(route('admin.categories.edit', $this->category));

        $response->assertStatus(200);
        $response->assertViewIs('admin.categories.edit');
        $response->assertSee('Старое название');
        $response->assertSee($this->category->color);
    }

    public function test_admin_can_update_category()
    {
        $newData = [
            'name' => 'Новое название',
            'color' => '#00ff00',
            'description' => 'Новое описание'
        ];

        $response = $this->actingAs($this->adminUser)
            ->put(route('admin.categories.update', $this->category), $newData);

        $response->assertRedirect(route('admin.categories.index'));

        // Проверяем что данные обновились в базе
        $this->assertDatabaseHas('categories', [
            'id' => $this->category->id,
            'name' => 'Новое название',
            'color' => '#00ff00',
            'description' => 'Новое описание'
        ]);

        $response->assertSessionHas('success');
    }

    public function test_cannot_update_category_with_existing_name()
    {
        // Создаем другую категорию
        $otherCategory = Category::factory()->create(['name' => 'Уникальное название']);

        $updateData = [
            'name' => 'Уникальное название', // пытаемся использовать уже занятое название
            'color' => '#00ff00',
            'description' => 'Описание'
        ];

        $response = $this->actingAs($this->adminUser)
            ->put(route('admin.categories.update', $this->category), $updateData);

        // Проверяем ВАЛИДАЦИОННЫЕ ошибки, а не сессионную ошибку
        $response->assertSessionHasErrors('name');

        // Или можно проверить конкретное сообщение
        $response->assertSessionHasErrors(['name' => 'The name has already been taken.']);

        // Проверяем что исходная категория не изменилась
        $this->assertDatabaseHas('categories', [
            'id' => $this->category->id,
            'name' => 'Старое название'
        ]);
    }

    public function test_can_update_category_with_same_name()
    {
        $updateData = [
            'name' => 'Старое название', // оставляем то же название
            'color' => '#00ff00',
            'description' => 'Новое описание'
        ];

        $response = $this->actingAs($this->adminUser)
            ->put(route('admin.categories.update', $this->category), $updateData);

        $response->assertRedirect(route('admin.categories.index'));
        $response->assertSessionHas('success');
    }

    public function test_edit_returns_404_for_nonexistent_category()
    {
        $response = $this->actingAs($this->adminUser)
            ->get(route('admin.categories.edit', 999));

        $response->assertStatus(404);
    }

    public function test_update_returns_404_for_nonexistent_category()
    {
        $updateData = [
            'name' => 'Новое название',
            'color' => '#00ff00',
            'description' => 'Описание'
        ];

        $response = $this->actingAs($this->adminUser)
            ->put(route('admin.categories.update', 999), $updateData);

        $response->assertStatus(404);
    }

    public function test_update_handles_category_already_exists_exception()
    {
        // Мок Handler чтобы он выбросил CategoryAlreadyExistsException
        $this->mock(Handler::class, function ($mock) {
            $mock->shouldReceive('handle')
                ->andThrow(new CategoryAlreadyExistsException('Тест'));
        });

        $updateData = [
            'name' => 'Тестовое название',
            'color' => '#ff0000',
            'description' => 'Описание'
        ];

        $response = $this->actingAs($this->adminUser)
            ->put(route('admin.categories.update', $this->category), $updateData);

        $response->assertRedirect();
        $response->assertSessionHas('error');
        $response->assertSessionHasInput('name', 'Тестовое название');
    }

    public function test_update_handles_unexpected_exception()
    {
        // Мок Handler чтобы он выбросил обычное Exception
        $this->mock(Handler::class, function ($mock) {
            $mock->shouldReceive('handle')
                ->andThrow(new \Exception('Неожиданная ошибка'));
        });

        $updateData = [
            'name' => 'Тестовое название',
            'color' => '#ff0000',
            'description' => 'Описание'
        ];

        $response = $this->actingAs($this->adminUser)
            ->put(route('admin.categories.update', $this->category), $updateData);

        $response->assertStatus(404);
    }
}
