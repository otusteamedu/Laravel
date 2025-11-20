<?php

namespace App\Repositories;


interface PostRepoInterface
{
    public function getRecentPosts(int $count): \Illuminate\Database\Eloquent\Collection;
    public function findById(int $id): \App\Models\Post;
}
