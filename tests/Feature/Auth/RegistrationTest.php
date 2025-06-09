<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register(): void
    {
        $response = $this->post('/register', [
            'name' => 'test123',
            'email' => 'test123@mail.ru',
            'password' => 'test123456',
            'password_confirmation' => 'test123456',
            'secondName' => 'test123',
            'lastName' => 'test123',
            'organization' => 'test123',
            'userRole' => 'emp'
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('profile.edit', absolute: false));
    }
}
