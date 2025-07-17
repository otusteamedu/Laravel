<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Services\CommentsService;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::all();
        return view('posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $post = Post::create([
            "title" => $request->title,
            "text" => $request->text,
            "is_draft" => $request->is_draft,
            "author_id" => $request->author_id,
        ]);

        return $post;
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post, CommentsService $commentsService)
    {
        return view('posts.show', compact('post'));
    }
    /**
     * Display the specified resource.
     */
    public function showComments(Post $post, CommentsService $commentsService)
    {
        // user -> current page -> handler -> 4 http call to service
        $comments = $commentsService->loadCommentsFor($post);
        return view('posts.comments', compact('post', 'comments'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        //
    }
}
