<?php

namespace Tests\Unit\Models;

use App\Models\News;
use App\Models\User;
use App\Models\Category;
use App\Models\Comment;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Tests\TestCase;

class NewsTest extends TestCase
{
    public function test_fillable_attributes()
    {
        $news = new News();
        $this->assertEquals([
            'title',
            'text',
            'thumbnail',
            'is_draft',
            'published_at'
        ], $news->getFillable());
    }

    public function test_casts()
    {
        $news = new News();
        $casts = $news->getCasts();
        
        $this->assertArrayHasKey('is_draft', $casts);
        $this->assertArrayHasKey('published_at', $casts);
        $this->assertEquals('boolean', $casts['is_draft']);
        $this->assertEquals('datetime', $casts['published_at']);
    }

    public function test_user_relation()
    {
        $news = new News();
        $relation = $news->user();
        
        $this->assertInstanceOf(BelongsTo::class, $relation);
        $this->assertInstanceOf(User::class, $relation->getRelated());
    }

    public function test_category_relation()
    {
        $news = new News();
        $relation = $news->category();
        
        $this->assertInstanceOf(BelongsTo::class, $relation);
        $this->assertInstanceOf(Category::class, $relation->getRelated());
    }

    public function test_comments_relation()
    {
        $news = new News();
        $relation = $news->comments();
        
        $this->assertInstanceOf(HasMany::class, $relation);
        $this->assertInstanceOf(Comment::class, $relation->getRelated());
    }
} 