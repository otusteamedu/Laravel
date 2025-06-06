<?php

namespace Tests\Unit\Models;

use App\Models\User;
use App\Models\News;
use App\Models\Comment;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Tests\TestCase;

class UserTest extends TestCase
{
    public function test_fillable_attributes()
    {
        $user = new User();
        $this->assertEquals(['name', 'email', 'password', 'is_admin'], $user->getFillable());
    }

    public function test_hidden_attributes()
    {
        $user = new User();
        $this->assertEquals(['password', 'remember_token'], $user->getHidden());
    }

    public function test_casts()
    {
        $user = new User();
        $casts = $user->getCasts();
        
        $this->assertArrayHasKey('email_verified_at', $casts);
        $this->assertArrayHasKey('password', $casts);
        $this->assertArrayHasKey('is_admin', $casts);
        $this->assertEquals('datetime', $casts['email_verified_at']);
        $this->assertEquals('hashed', $casts['password']);
        $this->assertEquals('boolean', $casts['is_admin']);
    }

    public function test_news_relation()
    {
        $user = new User();
        $relation = $user->news();
        
        $this->assertInstanceOf(HasMany::class, $relation);
        $this->assertInstanceOf(News::class, $relation->getRelated());
    }

    public function test_comments_relation()
    {
        $user = new User();
        $relation = $user->comments();
        
        $this->assertInstanceOf(HasMany::class, $relation);
        $this->assertInstanceOf(Comment::class, $relation->getRelated());
    }
} 