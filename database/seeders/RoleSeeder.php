<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::whereIn('name', ['editor', 'admin'])->get();
        $roles = Role::whereIn('slug', ['editor', 'admin'])->get();

        $editorRoles = [];
        $adminRoles = [];

        foreach ($roles as $role) {
            if ($role->slug === 'editor') {
                $editorRoles[] = $role->id;
                $adminRoles[] = $role->id;
            }

           if ($role->slug === 'admin') {
               $adminRoles[] = $role->id;
           }
        }

        foreach ($users as $user) {
            if ($user->name === 'admin') {
                $user->roles()->attach($adminRoles);
            } else if ($user->name === 'editor') {
                $user->roles()->attach($editorRoles);
            }
        }
    }
}
