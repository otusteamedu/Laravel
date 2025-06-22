<?php

namespace Tests\Feature\Admin\Users;

use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserCreateTest extends TestCase {
    use RefreshDatabase;

    private User $adminUser;

    protected function setUp(): void
    {
        parent::setUp();

        // Создаем администратора для тестов
        $this->adminUser = User::factory()->create(['is_admin' => true]);
    }
    public function test_admin_can_open_create_user_form() {
        $response = $this->actingAs($this->adminUser)
            ->get(route('admin.users.create'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.users.create');
    }

    public function test_admin_can_create_new_user() {
        $userData = [
            'name'        => 'Иванов Иван Иванович',
            'email'       => 'ivanov@laraveltest.io',
            'password' => 'password',
            'password_confirmation' => 'password',
            'is_admin'    => false,
        ];

        $response = $this->actingAs($this->adminUser)
            ->post(route('admin.users.store'), $userData);

        // Проверяем что нас перенаправили на список пользователей
        $response->assertRedirect(route('admin.users.index'));

        // Проверяем что в базе появился новый пользователь (без пароля и подтверждения)
        $this->assertDatabaseHas('users', [
            'name' => 'Иванов Иван Иванович',
            'email' => 'ivanov@laraveltest.io',
            'is_admin' => false,
        ]);

        // Проверяем что показано сообщение об успехе
        $response->assertSessionHas('success', "Пользователь 'Иванов Иван Иванович' успешно создан");
    }

    public function test_unauthorized_user_redirected_to_login() {
        $response = $this->get(route('admin.users.create'));
        $response->assertRedirect(route('login'));
    }
}
