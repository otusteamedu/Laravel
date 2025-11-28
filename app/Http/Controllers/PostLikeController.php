<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Services\PostLikeService\PostLikeServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class PostLikeController extends Controller
{
    public function likePost(Post $post, PostLikeServiceInterface $likeService)
    {
        Gate::authorize('like-post', $post);
        $user = auth()->user();
        $likeService->likePost($post, $user);

        return redirect()->back();
    }

    public function unlikePost(Post $post, PostLikeServiceInterface $likeService)
    {
        $user = auth()->user();
        $likeService->unlikePost($post, $user);

        return redirect()->back();
    }
}
