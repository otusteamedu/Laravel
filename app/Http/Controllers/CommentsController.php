<?php

namespace App\Http\Controllers;

use App\Models\Comment;

class CommentsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function forPost($postId)
    {
        return Comment::where('post_id', $postId)->get();
    }
}
