<?php

namespace App\Http\Controllers;

use App\DTO\CreatePostRequestDTO;
use App\Http\Requests\CreatePostRequest;
use App\Models\Post;
use App\Services\PostService\PostServiceInterface;
use App\VO\PostText;
use App\VO\PostTitle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(PostServiceInterface $postService)
    {
        if (!Auth::user()->can('viewAny', Post::class)) {
            abort(404);
        }

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
    public function store(CreatePostRequest $request, PostServiceInterface $postService)
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
        return $post;
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
