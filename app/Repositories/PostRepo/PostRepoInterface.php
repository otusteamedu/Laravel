<?php

namespace App\Repositories\PostRepo;

use App\DTO\CreatePostRequestDTO;
use App\Models\Like;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;


interface PostRepoInterface
{
    public function getRecentPosts(int $count): Collection;
    public function findById(int $id): Post;

    public function createPost(CreatePostRequestDTO $dto): Post;

    public function likePost(Post $post, User $user): Like;
    public function unlikePost(Post $post, User $user): void;

    public function getPostAuthor(Post $post): User;
}
