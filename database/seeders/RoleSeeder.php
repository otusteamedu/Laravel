<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::firstOrCreate(['name' => 'admin', 'description' => 'Administrator with full access']);
        Role::firstOrCreate(['name' => 'editor', 'description' => 'Can create and edit content']);
        Role::firstOrCreate(['name' => 'user', 'description' => 'Regular user']);
    }
}
