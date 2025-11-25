<?php

namespace App\Repositories\LikeRepo;

use App\Models\Post;
use App\Models\User;
use App\Repositories\LikeRepo\LikeRepoInterface;

class EloquentLikeRepo implements LikeRepoInterface
{
    public function likeExists(Post $post, User $user): bool
    {
        return $post->likes()->whereUserId($user->id)->exists();
    }
}
