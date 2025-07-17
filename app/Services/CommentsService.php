<?php

namespace App\Services;

use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Http;

class CommentsService
{
    public function loadCommentsFor(Post $post)
    {
        $data = collect(Http::get("localhost:81/comments_for/{$post->id}")->json());

        $authorIdsToLoad = $data->map->author_id;

        $authorIdToUser = User::whereIn('id', $authorIdsToLoad)->get()->keyBy('id');

        return $data->transform(function ($comment) use ($authorIdToUser) {
            $comment['author'] = $authorIdToUser[$comment['author_id']];

            return $comment;
        });
    }
}
