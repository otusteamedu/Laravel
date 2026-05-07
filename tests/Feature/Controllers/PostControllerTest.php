<?php

namespace Tests\Feature\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Services\WithdrawService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Mockery\MockInterface;
use PHPUnit\Framework\Attributes\Group;
use Tests\TestCase;

#[Group("post-controller")]
class PostControllerTest extends TestCase
{
    use RefreshDatabase;
    protected $postsIndex;

    protected function setUp(): void
    {
        parent::setUp();
        $this->postsIndex = route('posts.index');
        $this->postsStore = route('posts.store');

        $user = User::factory()->create();

        $this->actingAs($user);
    }

    public function test_all_posts_is_accessible(): void
    {
        $response = $this->getJson($this->postsIndex);

        $response->assertStatus(200);
    }

    public function test_all_posts_responds_with_empty_array_when_no_posts(): void
    {
        $response = $this->getJson($this->postsIndex);

        $response->assertJson([]);
    }

    public function test_all_posts_responds_with_one_post(): void
    {
        $post = Post::factory()->create();

        $response = $this->getJson($this->postsIndex);

        $response->assertJson([$post->toArray()]);
    }

    public function test_create_post()
    {
        $author = User::factory()->create();


        $expectedAmount = "100.00";
        $this->mock(WithdrawService::class, function (MockInterface $mock) use ($expectedAmount) {
            $mock->shouldReceive('withdraw')->andReturns($expectedAmount);
        });


        $payload = [
            "title" => 'post title',
            "text" => 'post text',
            "is_draft" => true,
            "author_id" => $author->id,
        ];

        $response = $this->postJson($this->postsStore, $payload);

        $response->assertStatus(200);

        $postId = $response->json('post.id');
        $actualAmount = $response->json('amount');

        $this->assertNotEmpty(Post::find($postId));
        $this->assertEquals($expectedAmount, $actualAmount);
    }
}
