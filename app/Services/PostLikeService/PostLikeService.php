<?php

namespace App\Services\PostLikeService;

use App\Jobs\SendNotificationJob;
use App\Models\Post;
use App\Models\User;
use App\Repositories\LikeRepo\LikeRepoInterface;
use App\Repositories\PostRepo\PostRepoInterface;
use App\Services\NotificationService\NotificationServiceInterface;
use App\VO\NotificationText;
use Log;

class PostLikeService implements PostLikeServiceInterface
{
    public function __construct(
        private readonly PostRepoInterface $postRepo,
        private readonly LikeRepoInterface $likeRepo,
    ) {

    }

    public function likePost(Post $post, User $user)
    {
        $likeExists = $this->likeRepo->likeExists($post, $user);
        if (!$likeExists) {
            $postAuthor = $this->postRepo->getPostAuthor($post);
            $this->postRepo->likePost($post, $user);
            $notificationText = new NotificationText("Ваш пост ({$post->id}, {$post->title}) был лайкнут");

            SendNotificationJob::dispatch($postAuthor, $notificationText);

            \Log::info("first");
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
