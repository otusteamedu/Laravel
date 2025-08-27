<?php

namespace Database\Seeders;

use App\Infrastructure\Eloquent\Models\Role;
use App\Infrastructure\Eloquent\Models\User;
use Illuminate\Database\Seeder;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $adminUser = User::where('email', 'admin@example.com')->first();
        $adminRole = Role::where('name', 'admin')->first();

        if ($adminUser && $adminRole) {
            $adminUser->roles()->attach($adminRole);
        }


        $editorUser = User::where('email', 'editor@example.com')->first();
        $editorRole = Role::where('name', 'editor')->first();

        if ($editorUser && $editorRole) {
            $editorUser->roles()->attach($editorRole);
        }


        $regularUser = User::where('email', 'user@example.com')->first();
        $userRole = Role::where('name', 'user')->first();

        if ($regularUser && $userRole) {
            $regularUser->roles()->attach($userRole);
        }
    }
}
