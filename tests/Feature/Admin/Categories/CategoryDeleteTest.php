<?php

namespace Tests\Feature\Admin\Categories;

use App\Models\Category;
use App\Models\Task;
use Tests\Feature\Admin\AdminTestCase;

class CategoryDeleteTest extends AdminTestCase
{
    private Category $category;

    protected function setUp(): void
    {
        parent::setUp();
        $this->category = Category::factory()->create();
    }

    public function test_guest_cannot_delete_category()
    {
        $this->assertGuestRedirectedToLogin("admin.categories.destroy", 'delete', [], ['category' => $this->category->id]);
    }

    public function test_regular_user_cannot_delete_category()
    {
        $this->asRegularUser()
            ->delete(route('admin.categories.destroy', $this->category))
            ->assertStatus(403);
    }

    public function test_admin_can_delete_category()
    {
        $this->asAdmin()
            ->delete(route('admin.categories.destroy', $this->category))
            ->assertRedirect(route('admin.categories.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('categories', ['id' => $this->category->id]);
    }

    public function test_delete_with_nonexistent_category()
    {
        $this->asAdmin()
            ->delete(route('admin.categories.destroy', ['category' => 999]))
            ->assertStatus(404);
    }

    public function test_cannot_delete_category_with_tasks()
    {
        // Создаем задачу для категории
        Task::factory()->create(['category_id' => $this->category->id]);

        $this->asAdmin()
            ->delete(route('admin.categories.destroy', $this->category))
            ->assertRedirect(route('admin.categories.index'))
            ->assertSessionHas('error');

        // Категория не должна быть удалена
        $this->assertDatabaseHas('categories', ['id' => $this->category->id]);
    }
} 