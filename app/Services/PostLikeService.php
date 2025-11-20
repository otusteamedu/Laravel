<?php

namespace App\Services;

use App\Models\Post;
use App\Models\User;
use App\Repositories\EloquentPostRepo;

class PostLikeService
{
    public function __construct(private EloquentPostRepo $postRepo)
    {

    }
    public function likePost(Post $post, User $user)
    {
        $this->postRepo->likePost($post, $user);
    }
}
