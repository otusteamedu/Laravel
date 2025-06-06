<?php

namespace Feature\Controllers\Admin\Category;

use App\Models\Category;
use App\Models\News;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Group;
use Tests\TestCase;

#[Group('admin-category')]
class DestroyControllerTest extends TestCase
{
    use RefreshDatabase;
    private const ROUTE_ADMIN_CATEGORY_INDEX = 'admin.categories.index';
    private const ROUTE_ADMIN_CATEGORY_DESTROY = 'admin.categories.destroy';

    public function test_it_deletes_category()
    {
        $user = User::factory()->create(['is_admin' => true]);
        $category = Category::factory()->create();

        $response = $this->actingAs($user)
            ->delete(route(self::ROUTE_ADMIN_CATEGORY_DESTROY, ['categoryId' => $category->id]));

        $response->assertRedirect(route(self::ROUTE_ADMIN_CATEGORY_INDEX))
            ->assertSessionHas('success', 'Category has been deleted');
        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }

    public function test_it_returns_404_for_non_existent_category()
    {
        $user = User::factory()->create(['is_admin' => true]);

        $response = $this->actingAs($user)
            ->delete(route(self::ROUTE_ADMIN_CATEGORY_DESTROY, ['categoryId' => 999]));

        $response->assertNotFound();
    }

    public function test_it_requires_authentication()
    {
        $category = Category::factory()->create();

        $response = $this->delete(route(self::ROUTE_ADMIN_CATEGORY_DESTROY, ['categoryId' => $category->id]));

        $response->assertRedirect(route('login'));
    }

    public function test_it_requires_admin_role()
    {
        $user = User::factory()->create(['is_admin' => false]);
        $category = Category::factory()->create();

        $response = $this->actingAs($user)
            ->delete(route(self::ROUTE_ADMIN_CATEGORY_DESTROY, ['categoryId' => $category->id]));

        $response->assertForbidden();
    }

    public function test_it_preserves_related_news_on_category_deletion()
    {
        $user = User::factory()->create(['is_admin' => true]);
        $category = Category::factory()->create();
        $news = News::factory()->count(3)->create(['category_id' => $category->id]);

        $response = $this->actingAs($user)
            ->delete(route(self::ROUTE_ADMIN_CATEGORY_DESTROY, ['categoryId' => $category->id]));

        $response->assertRedirect(route(self::ROUTE_ADMIN_CATEGORY_INDEX))
            ->assertSessionHas('success', 'Category has been deleted');

        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
        foreach ($news as $newsItem) {
            $this->assertDatabaseHas('news', ['id' => $newsItem->id]);
        }
    }

    public function test_it_handles_concurrent_deletion()
    {
        $user = User::factory()->create(['is_admin' => true]);
        $category = Category::factory()->create();

        // Simulate concurrent deletion by deleting the category first
        $category->delete();

        $response = $this->actingAs($user)
            ->delete(route(self::ROUTE_ADMIN_CATEGORY_DESTROY, ['categoryId' => $category->id]));

        $response->assertNotFound();
    }
}
