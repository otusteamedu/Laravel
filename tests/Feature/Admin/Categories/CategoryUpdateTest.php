<?php

namespace Tests\Feature\Admin\Categories;

use App\Models\Category;
use Tests\Feature\Admin\AdminTestCase;
use App\Services\Commands\UpdateCategory\Handler;
use App\Services\Exceptions\Categories\CategoryAlreadyExistsException;
class CategoryUpdateTest extends AdminTestCase
{
    private Category $category;

    protected function setUp(): void
    {
        parent::setUp();
        $this->category = Category::factory()->create();
    }

    public function test_guest_cannot_access_edit_form()
    {
        $this->assertGuestRedirectedToLogin("admin.categories.edit", 'get', [], ['category' => $this->category->id]);
    }

    public function test_regular_user_cannot_access_edit_form()
    {
        $this->asRegularUser()
            ->get(route('admin.categories.edit', $this->category))
            ->assertStatus(403);
    }

    public function test_admin_can_access_edit_form()
    {
        $this->asAdmin()
            ->get(route('admin.categories.edit', $this->category))
            ->assertStatus(200)
            ->assertViewIs('admin.categories.edit')
            ->assertViewHas('category')
            ->assertSee($this->category->name);
    }

    public function test_admin_can_access_edit_form_with_nonexistent_category()
    {
        $this->assert404ForNonexistentResource('admin.categories.edit', ['category' => 999]);
    }

    public function test_guest_cannot_update_category()
    {
        $this->assertGuestRedirectedToLogin("admin.categories.update", 'put', [
            'name' => 'Новое название',
            'color' => '#00ff00',
            'description' => 'Новое описание'
        ], ['category' => $this->category->id]);
    }

    public function test_regular_user_cannot_update_category()
    {
        $this->asRegularUser()
            ->put(route('admin.categories.update', $this->category), [
                'name' => 'Новое название',
                'color' => '#00ff00',
                'description' => 'Новое описание'
            ])
            ->assertStatus(403);
    }

    public function test_admin_can_update_category()
    {
        $updateData = [
            'name' => 'Новое название',
            'color' => '#00ff00',
            'description' => 'Новое описание'
        ];

        $this->asAdmin()
            ->put(route('admin.categories.update', $this->category), $updateData)
            ->assertRedirect(route('admin.categories.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('categories', array_merge(['id' => $this->category->id], $updateData));
    }

    public function test_update_with_nonexistent_category()
    {
        $this->asAdmin()
            ->put(route('admin.categories.update', ['category' => 999]), [
                'name' => 'Новое название',
                'color' => '#00ff00',
                'description' => 'Новое описание'
            ])
            ->assertStatus(404);
    }

    public function test_update_requires_name()
    {
        $this->asAdmin()
            ->put(route('admin.categories.update', $this->category), [
                'color' => '#00ff00',
                'description' => 'Updated Description'
            ])
            ->assertSessionHasErrors('name');
    }

    public function test_update_requires_color()
    {
        $this->asAdmin()
            ->put(route('admin.categories.update', $this->category), [
                'name' => 'Updated Category',
                'description' => 'Updated Description'
            ])
            ->assertSessionHasErrors('color');
    }

    public function test_update_handles_category_already_exists_exception()
    {
        // Мокаем Handler чтобы выбросить CategoryAlreadyExistsException
        $this->mock(Handler::class)
            ->shouldReceive('handle')
            ->andThrow(new CategoryAlreadyExistsException('Existing Category'));

        $this->asAdmin()
            ->put(route('admin.categories.update', $this->category), [
                'name' => 'Existing Category',
                'color' => '#00ff00',
                'description' => 'Updated Description'
            ])
            ->assertRedirect()
            ->assertSessionHas('error', "Категория с именем 'Existing Category' уже существует");
    }

    public function test_update_handles_general_exception()
    {
        // Мокаем Handler чтобы выбросить общее исключение
        $this->mock(Handler::class)
            ->shouldReceive('handle')
            ->andThrow(new \Exception('Неожиданная ошибка'));

        $this->asAdmin()
            ->put(route('admin.categories.update', $this->category), [
                'name' => 'Тестовое название',
                'color' => '#ff0000',
                'description' => 'Описание'
            ])
            ->assertStatus(404);
    }
}
