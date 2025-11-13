<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $comment = Post::first()->comments()->first();
        Comment::factory()->create(['parent_type' => Comment::class, 'parent_id' => $comment->id]);
    }
}
