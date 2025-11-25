<?php

namespace App\Services\PostLikeService;

use App\Models\Post;
use App\Models\User;

interface PostLikeServiceInterface
{
    public function likePost(Post $post, User $user);

    public function unlikePost(Post $post, User $user);
}
