<?php

namespace Database\Seeders;

use App\Models\Message;
use Illuminate\Database\Seeder;

class MessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Message::factory()->create(['user_id' => 1]);
        Message::factory()->create(['user_id' => 2]);
        Message::factory()->create(['user_id' => 3]);
    }
}
