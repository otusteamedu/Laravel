<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Services\PostLikeService;
use Illuminate\Http\Request;

class PostLikeController extends Controller
{
    public function likePost(Post $post, PostLikeService $likeService)
    {
        $user = auth()->user();
        $likeService->likePost($post, $user);

        return redirect()->back();
    }
}
