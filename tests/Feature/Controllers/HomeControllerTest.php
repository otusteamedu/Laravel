<?php

namespace Feature\Controllers;

use App\Http\Controllers\HomeController;
use Illuminate\View\View;
use PHPUnit\Framework\Attributes\Group;
use Tests\TestCase;

#[Group('home')]
class HomeControllerTest extends TestCase
{
    private HomeController $controller;

    private const ROUTE_LOGIN = 'login';
    private const ROUTE_HOME = 'home';

    protected function setUp(): void
    {
        parent::setUp();
        $this->controller = new HomeController();
    }

    public function test_invoke_returns_home_view()
    {
        $result = $this->controller->__invoke();

        $this->assertInstanceOf(View::class, $result);
        $this->assertEquals('home', $result->getName());
    }

    public function test_home_route_redirects_to_login_for_guest()
    {
        $response = $this->get(route(self::ROUTE_HOME));

        $response->assertStatus(302);
        $response->assertRedirect(route(self::ROUTE_LOGIN));
    }

    public function test_home_route_returns_200_for_authenticated_user()
    {
        $user = \App\Models\User::factory()->create();

        $response = $this->actingAs($user)->get(self::ROUTE_HOME);

        $response->assertStatus(200);
        $response->assertViewIs('home');
    }

    public function test_root_route_returns_welcome_view()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertViewIs('welcome');
        $response->assertViewHas('services', ['Услуга 1', 'Услуга 2', 'Услуга 3']);
    }
}
