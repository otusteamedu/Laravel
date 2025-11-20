<?php

namespace App\Http\Controllers;

use App\DTO\CreatePostRequestDTO;
use App\Http\Requests\CreatePostRequest;
use App\Models\Post;
use App\Services\PostService;
use App\VO\PostText;
use App\VO\PostTitle;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(PostService $postService)
    {
        $posts = $postService->getRecentPosts();
        return view('posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreatePostRequest $request, PostService $postService)
    {
        $title = new PostTitle($request->title);
        $text = new PostText($request->text);
        $author = \Auth::user();
        $dto = new CreatePostRequestDTO($title, $text, $author);

        $postService->createPost($dto);

        return redirect()->route('posts.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        return view('edit_post', compact('post'));
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
