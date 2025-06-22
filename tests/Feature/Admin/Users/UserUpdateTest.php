<?php

namespace Tests\Feature\Admin\Users;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserUpdateTest extends TestCase {
    use RefreshDatabase;

    private User $adminUser;

    private User $testUser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->adminUser = User::factory()->create(['is_admin' => true]);
        $this->testUser  = User::factory()->create(
            [
                'name'     => 'Петров Петр Петрович',
                'email'    => 'petrov@laraveltest.io',
                'password' => 'password',
                'is_admin' => false,
            ]
        );
    }

    public function test_admin_can_open_edit_form()
    {
        $response = $this->actingAs($this->adminUser)
            ->get(route('admin.users.edit', $this->testUser));

        $response->assertStatus(200);
        $response->assertViewIs('admin.users.edit');
        $response->assertSee('Петров Петр Петрович');
        $response->assertSee($this->testUser->email);
    }

    public function test_admin_can_update_user()
    {
        $newData = [
            'name'                  => 'Петров Александр Петрович',
            'email'                 => 'petrov.alex@laraveltest.io',
            'password'              => 'password2',
            'password_confirmation' => 'password2',
        ];

        $response = $this->actingAs($this->adminUser)
            ->put(route('admin.users.update', $this->testUser), $newData);

        $response->assertRedirect(route('admin.users.index'));

        // Проверяем что данные обновились в базе
        $this->assertDatabaseHas(
            'users', [
                       'id'    => $this->testUser->id,
                       'name'  => 'Петров Александр Петрович',
                       'email' => 'petrov.alex@laraveltest.io',
                   ]
        );

        $response->assertSessionHas('success');
    }

    public function test_cannot_update_category_with_existing_email()
    {
        // Создаем другого пользователя
        $otherUser = User::factory()->create(['email' => 'other.user@example.com']);

        $updateData = [
            'name' => 'Петров Петр Петрович',
            'email' => 'other.user@example.com', // пытаемся использовать уже занятый email
        ];

        $response = $this->actingAs($this->adminUser)
            ->put(route('admin.users.update', $this->testUser), $updateData);

        // Проверяем ошибки, а не сессионную ошибку
        $response->assertSessionHasErrors('email');

        // Или можно проверить конкретное сообщение
        $response->assertSessionHasErrors(['email' => 'The email has already been taken.']);

        // Проверяем что исходная почта не изменилась
        $this->assertDatabaseHas('users', [
            'id' => $this->testUser->id,
            'email' => 'petrov@laraveltest.io'
        ]);
    }

    public function test_edit_returns_404_for_nonexistent_user()
    {
        $response = $this->actingAs($this->adminUser)
            ->get(route('admin.users.edit', 999));

        $response->assertStatus(404);
    }

    public function test_update_returns_404_for_nonexistent_category()
    {
        $updateData = [
            'name' => 'Петров Петр Петрович',
            'email' => 'other.user@example.com',
        ];

        $response = $this->actingAs($this->adminUser)
            ->put(route('admin.users.update', 999), $updateData);

        $response->assertStatus(404);
    }

}
