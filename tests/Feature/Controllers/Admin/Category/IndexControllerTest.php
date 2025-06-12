<?php

namespace Feature\Controllers\Admin\Category;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Group;
use Tests\TestCase;

#[Group('admin-category')]
class IndexControllerTest extends TestCase
{
    use RefreshDatabase;

    private const ROUTE_ADMIN_CATEGORY_INDEX = 'admin.categories.index';

    public function test_it_displays_categories_list()
    {
        $user = User::factory()->create(['is_admin' => true]);
        $categories = Category::factory()->count(3)->create();

        $response = $this->actingAs($user)
            ->get(route(self::ROUTE_ADMIN_CATEGORY_INDEX));

        $response->assertOk()
            ->assertViewIs(self::ROUTE_ADMIN_CATEGORY_INDEX)
            ->assertViewHas('categories');

        foreach ($categories as $category) {
            $response->assertSee($category->name);
        }
    }

    public function test_it_displays_empty_categories_list()
    {
        $user = User::factory()->create(['is_admin' => true]);

        $response = $this->actingAs($user)
            ->get(route(self::ROUTE_ADMIN_CATEGORY_INDEX));

        $response->assertOk()
            ->assertViewIs(self::ROUTE_ADMIN_CATEGORY_INDEX)
            ->assertViewHas('categories');
    }

    public function test_it_requires_authentication()
    {
        $response = $this->get(route(self::ROUTE_ADMIN_CATEGORY_INDEX));

        $response->assertRedirect(route('login'));
    }

    public function test_it_requires_admin_role()
    {
        $user = User::factory()->create(['is_admin' => false]);

        $response = $this->actingAs($user)
            ->get(route(self::ROUTE_ADMIN_CATEGORY_INDEX));

        $response->assertForbidden();
    }
}
