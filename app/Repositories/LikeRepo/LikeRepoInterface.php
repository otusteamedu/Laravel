<?php

namespace App\Repositories\LikeRepo;

use App\Models\Post;
use App\Models\User;

interface LikeRepoInterface
{
    public function likeExists(Post $post, User $user): bool;
}
