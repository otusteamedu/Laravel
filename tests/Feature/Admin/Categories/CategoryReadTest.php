<?php

namespace Tests\Feature\Admin\Categories;

use Tests\TestCase;
use App\Models\User;
use App\Models\Category;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoryReadTest extends TestCase {
    use RefreshDatabase;

    private User $adminUser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->adminUser = User::factory()->create(['is_admin' => true]);
    }

    public function test_admin_can_read_categories()
    {
        // Создаем несколько категорий
        $categories = Category::factory()->count(3)->create();

        $response = $this->actingAs($this->adminUser)
            ->get(route('admin.categories.index'));

        $response->assertStatus(200);

        // Проверяем что все категории отображаются
        foreach ($categories as $category) {
            $response->assertSee($category->name);
        }
    }

    public function test_pagination_works_in_categories_list()
    {
        // Создаем 15 категорий
        Category::factory()->count(15)->create();

        $response = $this->actingAs($this->adminUser)
            ->get(route('admin.categories.index'));

        $response->assertStatus(200);

        // Проверяем что передана переменная с пагинацией
        $response->assertViewHas('categories');

        // Или проверяем наличие навигации пагинации в HTML
        $response->assertSee('pagination');
    }

}
