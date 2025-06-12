<?php

namespace Feature\Controllers\Admin;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Group;
use Tests\TestCase;

#[Group('admin-dashboard')]
class DashboardControllerTest extends TestCase
{
    use RefreshDatabase;

    private const ROUTE_ADMIN_DASHBOARD = 'admin.dashboard';
    private const ROUTE_LOGIN = 'login';

    public function test_it_show()
    {
        $user = User::factory()->create(['is_admin' => true]);

        $response = $this->actingAs($user)
                         ->get(route(self::ROUTE_ADMIN_DASHBOARD));

        $response->assertOk()
                 ->assertViewIs(self::ROUTE_ADMIN_DASHBOARD);
    }

    public function test_it_requires_authentication()
    {
        $response = $this->get(route(self::ROUTE_ADMIN_DASHBOARD));
        $response->assertRedirect(route(self::ROUTE_LOGIN));
    }

    public function test_it_requires_admin_role()
    {
        $user = User::factory()->create(['is_admin' => false]);

        $response = $this->actingAs($user)
                         ->get(route(self::ROUTE_ADMIN_DASHBOARD));

        $response->assertForbidden();
    }
}
