<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Modules\ISS\database\seeders\BaseISSSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        //User::factory()->create(['name' => 'Test User', 'email' => 'test@example.com']);

        $userData = [
            ['name' => 't1', 'email' => 't1@mail.ru', 'password' => 't1@mail.ru', 'organization' => 'org1', 'second_name' => 't1', 'last_name' => 't1', 'user_role' => 'emp'],
            ['name' => 't2', 'email' => 't2@mail.ru', 'password' => 't2@mail.ru', 'organization' => 'org1', 'second_name' => 't2', 'last_name' => 't2', 'user_role' => 'emp'],
            ['name' => 't3', 'email' => 't3@mail.ru', 'password' => 't3@mail.ru', 'organization' => 'org1', 'second_name' => 't3', 'last_name' => 't3', 'user_role' => 'admin'],
            ['name' => 't4', 'email' => 't4@mail.ru', 'password' => 't4@mail.ru', 'organization' => 'org1', 'second_name' => 't4', 'last_name' => 't4', 'user_role' => 'admin'],
            ['name' => 't5', 'email' => 't5@mail.ru', 'password' => 't5@mail.ru', 'organization' => 'org2', 'second_name' => 't5', 'last_name' => 't5', 'user_role' => 'emp'],
            ['name' => 't6', 'email' => 't6@mail.ru', 'password' => 't6@mail.ru', 'organization' => 'org2', 'second_name' => 't6', 'last_name' => 't6', 'user_role' => 'emp'],
            ['name' => 't7', 'email' => 't7@mail.ru', 'password' => 't7@mail.ru', 'organization' => 'org2', 'second_name' => 't7', 'last_name' => 't7', 'user_role' => 'admin'],
            ['name' => 't8', 'email' => 't8@mail.ru', 'password' => 't8@mail.ru', 'organization' => 'no', 'second_name' => 't8', 'last_name' => 't8', 'user_role' => 'emp'],
        ];
        foreach ($userData as $data) {
            User::factory()->create(
                [
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'password' => $data['password'],
                    'organization' => $data['organization'],
                    'second_name' => $data['second_name'],
                    'last_name' => $data['last_name'],
                    'user_role' => $data['user_role']
                ]
            );
        }

        $this->call(BaseISSSeeder::class);
    }
}
