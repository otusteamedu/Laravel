<?php

namespace App\Services\PostLikeService;

use App\Models\Post;
use App\Models\User;
use App\Repositories\LikeRepo\LikeRepoInterface;
use App\Repositories\PostRepo\PostRepoInterface;
use App\Services\NotificationService\NotificationServiceInterface;
use App\VO\NotificationText;

class PostLikeService implements PostLikeServiceInterface
{
    public function __construct(
        private readonly PostRepoInterface $postRepo,
        private readonly LikeRepoInterface $likeRepo,
        private readonly NotificationServiceInterface $notificationService,
    ) {

    }

    public function likePost(Post $post, User $user)
    {
        $likeExists = $this->likeRepo->likeExists($post, $user);
        if (!$likeExists) {
            $postAuthor = $this->postRepo->getPostAuthor($post);
            $this->postRepo->likePost($post, $user);
            $this->notificationService->notify(
                $postAuthor,
                new NotificationText("Ваш пост ({$post->id}, {$post->title}) был лайкнут")
            );
        }
    }

    public function unlikePost(Post $post, User $user)
    {
        $likeExists = $this->likeRepo->likeExists($post, $user);

        if ($likeExists) {
            $this->postRepo->unlikePost($post, $user);
        }
    }
}
