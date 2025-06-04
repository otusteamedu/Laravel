<?php

namespace Tests\Feature\Admin;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Group;
use Tests\TestCase;

#[Group('admin-dashboard')]
class DashboardControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_show()
    {
        $user = User::factory()->create(['is_admin' => true]);

        $response = $this->actingAs($user)
                         ->get(route('admin.dashboard'));

        $response->assertOk()
                 ->assertViewIs('admin.dashboard');
    }

    public function test_it_requires_authentication()
    {
        $response = $this->get(route('admin.dashboard'));

        $response->assertRedirect(route('login'));
    }

    public function test_it_requires_admin_role()
    {
        $user = User::factory()->create(['is_admin' => false]);

        $response = $this->actingAs($user)
                         ->get(route('admin.dashboard'));

        $response->assertForbidden();
    }
}
