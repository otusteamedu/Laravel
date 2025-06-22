<?php

namespace Tests\Feature\Admin\Categories;

use App\Models\Category;
use Tests\Feature\Admin\AdminTestCase;
use App\Services\Commands\CreateCategory\Handler;
use App\Services\Exceptions\Categories\CategorySaveException;
class CategoryCreateTest extends AdminTestCase
{
    public function test_guest_cannot_access_create_form()
    {
        $this->assertGuestRedirectedToLogin('admin.categories.create');
    }

    public function test_regular_user_cannot_access_create_form()
    {
        $this->asRegularUser()
            ->get(route('admin.categories.create'))
            ->assertStatus(403);
    }

    public function test_admin_can_access_create_form()
    {
        $this->asAdmin()
            ->get(route('admin.categories.create'))
            ->assertStatus(200)
            ->assertViewIs('admin.categories.create');
    }

    public function test_guest_cannot_create_category()
    {
        $this->assertGuestRedirectedToLogin('admin.categories.store', 'post', [
            'name'        => 'Спорт',
            'color'       => '#00ff00',
            'description' => 'Спортивные задачи'
        ]);
    }

    public function test_simple_user_cannot_create_category()
    {
        $this->asRegularUser()
            ->post(route('admin.categories.store'), [
                'name'        => 'Спорт',
                'color'       => '#00ff00',
                'description' => 'Спортивные задачи'
            ])
            ->assertStatus(403);
    }

    public function test_admin_can_create_category()
    {
        $categoryData = [
            'name'        => 'Тест',
            'color'       => 'красный', // неправильный формат цвета
            'description' => 'Описание'
        ];

        $this->asAdmin()
            ->post(route('admin.categories.store'), $categoryData)
            ->assertRedirect(route('admin.categories.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('categories', $categoryData);
    }

    public function test_create_requires_name()
    {
        $this->asAdmin()
            ->post(route('admin.categories.store'), [
                'name'        => 'Работа', // такое название уже есть
                'color'       => '#00ff00',
                'description' => 'Другое описание'
            ])
            ->assertSessionHasErrors('name');
    }

    public function test_create_requires_color()
    {
        $this->asAdmin()
            ->post(route('admin.categories.store'), [
                'name' => 'Тестовая категория',
                'description' => 'Описание'
            ])
            ->assertSessionHasErrors('color');
    }

    public function test_create_handles_category_already_exists_exception()
    {
        Category::factory()->create(['name' => 'Existing Category']);

        $this->asAdmin()
            ->post(route('admin.categories.store'), [
                'name' => 'Тестовая категория',
                'color' => '#ff0000',
                'description' => 'Описание'
            ])
            ->assertRedirect()
            ->assertSessionHas('error');
    }

    public function test_create_handles_category_save_exception()
    {
        // Мокаем Handler чтобы выбросить CategorySaveException
        $this->mock(Handler::class)
            ->shouldReceive('handle')
            ->andThrow(new CategorySaveException('Ошибка сохранения'));

        $this->asAdmin()
            ->post(route('admin.categories.store'), [
                'name' => 'Тестовая категория',
                'color' => '#ff0000',
                'description' => 'Описание'
            ])
            ->assertRedirect()
            ->assertSessionHas('error', 'Ошибка сохранения');
    }

    public function test_create_handles_general_exception()
    {
        // Мокаем Handler чтобы выбросить общее исключение
        $this->mock(Handler::class)
            ->shouldReceive('handle')
            ->andThrow(new \Exception('Неожиданная ошибка'));

        $this->asAdmin()
            ->post(route('admin.categories.store'), [
                'name' => 'Тестовая категория',
                'color' => '#ff0000',
                'description' => 'Описание'
            ])
            ->assertRedirect()
            ->assertSessionHas('error', 'Произошла непредвиденная ошибка при создании категории. Попробуйте позже.');
    }
}
