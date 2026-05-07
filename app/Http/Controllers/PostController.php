<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Services\WithdrawService;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Post::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, WithdrawService $withdrawService)
    {
        $post = Post::create($request->all());

        $amount = $withdrawService->withdraw($request->user(), "100.00");

        return compact("post", "amount");
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
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
