<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        \App\Models\User::factory(10)->create()->each(function ($user) {
            $roles = \App\Models\Role::inRandomOrder()->take(rand(1, 2))->pluck('id');
            $user->roles()->attach($roles);
        });
    }
}
