<?php

namespace Tests\Unit\Models;

use App\Models\Comment;
use App\Models\News;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Tests\TestCase;

class CommentTest extends TestCase
{
    public function test_fillable_attributes()
    {
        $comment = new Comment();
        $this->assertEquals(['text'], $comment->getFillable());
    }

    public function test_parent_relation()
    {
        $comment = new Comment();
        $relation = $comment->parent();
        
        $this->assertInstanceOf(BelongsTo::class, $relation);
        $this->assertInstanceOf(Comment::class, $relation->getRelated());
    }

    public function test_comments_relation()
    {
        $comment = new Comment();
        $relation = $comment->comments();
        
        $this->assertInstanceOf(HasMany::class, $relation);
        $this->assertInstanceOf(Comment::class, $relation->getRelated());
    }

    public function test_children_comments_relation()
    {
        $comment = new Comment();
        $relation = $comment->childrenComments();
        
        $this->assertInstanceOf(HasMany::class, $relation);
        $this->assertInstanceOf(Comment::class, $relation->getRelated());
    }

    public function test_news_item_relation()
    {
        $comment = new Comment();
        $relation = $comment->newsItem();
        
        $this->assertInstanceOf(BelongsTo::class, $relation);
        $this->assertInstanceOf(News::class, $relation->getRelated());
    }

    public function test_user_relation()
    {
        $comment = new Comment();
        $relation = $comment->user();
        
        $this->assertInstanceOf(BelongsTo::class, $relation);
        $this->assertInstanceOf(User::class, $relation->getRelated());
    }
} 