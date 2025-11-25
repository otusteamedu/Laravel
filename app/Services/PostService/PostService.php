<?php

namespace App\Services\PostService;

use App\DTO\CreatePostRequestDTO;
use App\Models\Post;
use App\Repositories\PostRepo\PostRepoInterface;


class PostService implements PostServiceInterface
{
    public function __construct(private PostRepoInterface $postRepo)
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
