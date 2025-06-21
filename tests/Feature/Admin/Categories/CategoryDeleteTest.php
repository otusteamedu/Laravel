<?php
namespace Tests\Feature\Admin\Categories;

use Tests\TestCase;
use App\Models\User;
use App\Models\Category;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoryDeleteTest extends TestCase
{
    use RefreshDatabase;

    private User $adminUser;
    protected function setUp(): void
    {
        parent::setUp();
        $this->adminUser = User::factory()->create(['is_admin' => true]);
    }

    public function test_admin_can_delete_empty_category()
    {
        $category = Category::factory()->create(['name' => 'Для удаления']);

        $response = $this->actingAs($this->adminUser)
            ->delete(route('admin.categories.destroy', $category));

        $response->assertRedirect(route('admin.categories.index'));

        // Проверяем что категория удалилась из базы
        $this->assertDatabaseMissing('categories', ['id' => $category->id]);

        $response->assertSessionHas('success');
    }

    public function test_admin_cannot_delete_category_with_tasks()
    {
        $category = Category::factory()->create();

        // Создаем задачи в этой категории
        Task::factory()->count(3)->create(['category_id' => $category->id]);

        $response = $this->actingAs($this->adminUser)
            ->delete(route('admin.categories.destroy', $category));

        // Проверяем что получили ошибку
        $response->assertSessionHas('error');

        // Проверяем что категория НЕ удалилась
        $this->assertDatabaseHas('categories', ['id' => $category->id]);
    }

    public function test_simple_user_cannot_delete_category()
    {
        $regularUser = User::factory()->create(['is_admin' => false]);
        $category = Category::factory()->create();

        $response = $this->actingAs($regularUser)
            ->delete(route('admin.categories.destroy', $category));

        $response->assertStatus(403);

        // Проверяем что категория не удалилась
        $this->assertDatabaseHas('categories', ['id' => $category->id]);
    }

    public function test_returns_404_for_nonexistent_category()
    {
        $response = $this->actingAs($this->adminUser)
            ->delete(route('admin.categories.destroy', 999));

        $response->assertStatus(404);
    }
}
