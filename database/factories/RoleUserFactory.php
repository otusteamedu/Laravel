<?php

namespace Database\Factories;

use App\Models\RoleUser;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

/**
 * @extends Factory<RoleUser>
 */
class RoleUserFactory extends Factory
{
    #[\Override]
    public function definition(): array
    {
        $roles = DB::table('roles')->select('id')->get();
        return [
            'user_id' => User::factory(),
            'role_id' => $roles->random()->id,
        ];
    }
}
