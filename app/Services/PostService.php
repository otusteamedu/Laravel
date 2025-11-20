<?php

namespace App\Services;

use App\DTO\CreatePostRequestDTO;
use App\Models\Post;
use App\Repositories\EloquentPostRepo;


class PostService
{
    public function __construct(private EloquentPostRepo $postRepo)
    {

    }

    public function getRecentPosts(): \Illuminate\Support\Collection
    {
        return $this->postRepo->getRecentPosts(3);
    }

    public function createPost(
        CreatePostRequestDTO $createPostRequestDTO
    ): Post {
        return $this->postRepo->createPost($createPostRequestDTO);
    }
}
