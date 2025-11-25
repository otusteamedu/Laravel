<?php

namespace App\Repositories\PostRepo;

use App\DTO\CreatePostRequestDTO;
use App\Models\Like;
use App\Models\Post;
use App\Models\User;

class EloquentPostRepo implements PostRepoInterface
{
    public function getRecentPosts(int $count): \Illuminate\Database\Eloquent\Collection
    {
        return Post::orderByDesc("created_at")->take($count)->get();
    }

    public function findById(int $id): \App\Models\Post
    {
        return Post::findOrFail($id);
    }

    public function createPost(CreatePostRequestDTO $dto): Post
    {
        return Post::create([
            'title' => $dto->title->getTitle(),
            'text' => $dto->text->text,
            'author_id' => $dto->author->id,
        ]);
    }

    public function likePost(Post $post, User $user): Like
    {
        $like = new Like();
        $like->user()->associate($user);
        $like->likable()->associate($post);
        $like->save();

        return $like;
    }

    /**
     * @inheritDoc
     */
    public function unlikePost(Post $post, User $user): void
    {
        $post->likes()->whereUserId($user->id)->delete();
    }

    /**
     * @inheritDoc
     */
    public function getPostAuthor(Post $post): User
    {
        return $post->author;
    }
}
