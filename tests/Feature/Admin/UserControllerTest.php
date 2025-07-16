<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Group;
use Tests\TestCase;
use Hash;

#[Group('user')]
class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpTheTestEnvironment();
    }

    public function test_user_list()
    {
        $user = User::factory()->create(['role_id' => 2]);
        User::factory()->create(['name' => 'Bob']);
        User::factory()->create(['name' => 'Alice']);
        User::factory()->create(['name' => 'Cantana']);

        $url = route('admin.users.index') . '?sort=name&direction=asc';
        $response = $this->actingAs($user)->get($url);

        $response->assertOk();
        $response->assertViewIs('admin.users.index');
        $response->assertViewHas('users', function ($users) {
            $condition1 = $users[0]->name === 'Alice';
            $condition2 = $users[1]->name === 'Bob';
            $condition3 = $users[2]->name === 'Cantana';
            return $condition1 && $condition2 && $condition3;
        });
        $response->assertSee('Alice');
        $response->assertSee('Bob');
        $response->assertSee('Cantana');
    }

    public function test_user_show()
    {
        $user = User::factory()->create(['role_id' => 1]);
        $user2 = User::factory()->create([
            'name' => 'testname',
            'email' => 'test@test.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => 'aaaaaaaaaa',
            'role_id' => 3,
            'created_at' => now(),
        ]);

        $url = route('admin.users.show', $user2->id);
        $response = $this->actingAs($user)->get($url);

        $response->assertOk();
        $response->assertViewIs('admin.users.show');
        $response->assertViewHas('userId', $user2->id);
        $response->assertViewHas('name', 'testname');
        $response->assertViewHas('email', 'test@test.com');
        $response->assertViewHas('emailVerified');
        $response->assertViewHas('role', 'Пользователь');
        $response->assertViewHas('createdAt');
        $response->assertSee('testname');
        $response->assertSee('test@test.com');
        $response->assertSee('Пользователь');

        $url = route('admin.users.show', 100);
        $response = $this->actingAs($user)->get($url);

        $response->assertNotFound();
    }

    public function test_user_create()
    {
        $user = User::factory()->create(['role_id' => 2]);

        $url = route('admin.users.create');
        $response = $this->actingAs($user)->get($url);

        $response->assertOk();
        $response->assertViewIs('admin.users.create');
        $response->assertSee('Имя');
        $response->assertSee('Email');
        $response->assertSee('Пароль');
        $response->assertSee('Роль');
    }

    public function test_user_store()
    {
        $user = User::factory()->create(['role_id' => 2]);
        $data = [
            'name' => 'testname',
            'email' => 'test@test.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'role_id' => 1,
        ];

        $url = route('admin.users.store');
        $response = $this->actingAs($user)->post($url, $data);

        $redirectUrl = route('admin.users.index');
        unset($data['password']);
        unset($data['password_confirmation']);
        $response->assertRedirect($redirectUrl);
        $this->assertDatabaseHas('users', $data);
    }

    public function test_user_edit()
    {
        $user = User::factory()->create(['role_id' => 1]);
        $user2 = User::factory()->create([
            'name' => 'testname',
            'email' => 'test@test.com'
        ]);

        $url = route('admin.users.edit', $user2->id);
        $response = $this->actingAs($user)->get($url);

        $response->assertOk();
        $response->assertViewIs('admin.users.edit');
        $response->assertViewHas('userId', $user2->id);
        $response->assertViewHas('name', 'testname');
        $response->assertViewHas('email', 'test@test.com');
        $response->assertViewHas('role_id');
        $response->assertViewHas('roles');
        $response->assertSee('testname');
        $response->assertSee('test@test.com');

        $url = route('admin.users.edit', 100);
        $response = $this->actingAs($user)->get($url);

        $response->assertNotFound();
    }

    public function test_user_update()
    {
        $user = User::factory()->create(['role_id' => 1]);
        $user2 = User::factory()->create();
        $newData = [
            'name' => 'updated_name',
            'email' => 'updated@test.com',
            'role_id' => 1,
        ];

        $url = route('admin.users.update', $user2->id);
        $response = $this->actingAs($user)->put($url, $newData);

        $redirectUrl = route('admin.users.index');
        $response->assertRedirect($redirectUrl);
        $this->assertDatabaseHas('users', array_merge(['id' => $user2->id], $newData));

        $url = route('admin.users.update', 100);
        $response = $this->actingAs($user)->put($url, $newData);

        $response->assertNotFound();
    }

    public function test_user_destroy()
    {
        $user = User::factory()->create(['role_id' => 1]);
        $user2 = User::factory()->create();

        $url = route('admin.users.destroy', $user2->id);
        $response = $this->actingAs($user)->delete($url);

        $redirectUrl = route('admin.users.index');
        $response->assertRedirect($redirectUrl);
        $this->assertDatabaseMissing('users', ['id' => $user2->id]);

        $url = route('admin.users.destroy', 100);
        $response = $this->actingAs($user)->delete($url);

        $response->assertNotFound();
    }
}
