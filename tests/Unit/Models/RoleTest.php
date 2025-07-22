<?php

namespace Tests\Unit\Models;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_be_created()
    {
        $role = Role::factory()->create();

        $this->assertDatabaseHas('roles', ['id' => $role->id]);
    }

    public function test_it_has_many_users()
    {
        $role = Role::factory()->create();
        $users = User::factory()->count(3)->create();

        // Привяжем пользователей к роли
        $role->users()->attach($users->pluck('id'));

        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $role->users);
        $this->assertCount(3, $role->users);
        $this->assertTrue($role->users->contains($users->first()));
    }
}
