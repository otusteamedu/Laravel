<?php

namespace App\Services\PostService;

use App\DTO\CreatePostRequestDTO;

interface PostServiceInterface
{
    public function getRecentPosts();
    public function createPost(CreatePostRequestDTO $newPostDTO);
}
