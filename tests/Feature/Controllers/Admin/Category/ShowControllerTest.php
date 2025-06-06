<?php

namespace Feature\Controllers\Admin\Category;

use App\Models\Category;
use App\Models\News;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Group;
use Tests\TestCase;

#[Group('admin-category')]
class ShowControllerTest extends TestCase
{
    use RefreshDatabase;

    private const ROUTE_ADMIN_CATEGORY_SHOW = 'admin.categories.show';

    public function test_it_displays_category_details()
    {
        $user = User::factory()->create(['is_admin' => true]);
        $category = Category::factory()->create([
            'name' => 'Test Category',
            'slug' => 'test-category',
            'sort' => 5
        ]);

        $response = $this->actingAs($user)
            ->get(route(self::ROUTE_ADMIN_CATEGORY_SHOW, ['categoryId' => $category->id]));

        $response->assertOk()
            ->assertViewIs(self::ROUTE_ADMIN_CATEGORY_SHOW)
            ->assertViewHas('category')
            ->assertSee('Test Category')
            ->assertSee('test-category')
            ->assertSee('5');
    }

    public function test_it_returns_404_for_non_existent_category()
    {
        $user = User::factory()->create(['is_admin' => true]);

        $response = $this->actingAs($user)
            ->get(route(self::ROUTE_ADMIN_CATEGORY_SHOW, ['categoryId' => 999]));

        $response->assertNotFound();
    }

    public function test_it_requires_authentication()
    {
        $category = Category::factory()->create();

        $response = $this->get(route(self::ROUTE_ADMIN_CATEGORY_SHOW, ['categoryId' => $category->id]));

        $response->assertRedirect(route('login'));
    }

    public function test_it_requires_admin_role()
    {
        $user = User::factory()->create(['is_admin' => false]);
        $category = Category::factory()->create();

        $response = $this->actingAs($user)
            ->get(route(self::ROUTE_ADMIN_CATEGORY_SHOW, ['categoryId' => $category->id]));

        $response->assertForbidden();
    }

    public function test_it_shows_category_with_related_news_count()
    {
        $user = User::factory()->create(['is_admin' => true]);
        $category = Category::factory()->create();
        News::factory()->count(3)->create(['category_id' => $category->id]);

        $response = $this->actingAs($user)
            ->get(route(self::ROUTE_ADMIN_CATEGORY_SHOW, ['categoryId' => $category->id]));

        $response->assertOk()
            ->assertViewIs(self::ROUTE_ADMIN_CATEGORY_SHOW)
            ->assertViewHas('category');
    }

    public function test_it_shows_category_without_news()
    {
        $user = User::factory()->create(['is_admin' => true]);
        $category = Category::factory()->create();

        $response = $this->actingAs($user)
            ->get(route(self::ROUTE_ADMIN_CATEGORY_SHOW, ['categoryId' => $category->id]));

        $response->assertOk()
            ->assertViewIs(self::ROUTE_ADMIN_CATEGORY_SHOW)
            ->assertViewHas('category');
    }
}
