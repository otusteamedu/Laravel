<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\Group;
use Tests\TestCase;

#[Group("posts")]
class PostControllerTest extends TestCase
{
    use RefreshDatabase;
    protected $indexUrl;
    protected $createUrl;

    protected function setUp(): void
    {
        $this->setUpTheTestEnvironment();
        $this->indexUrl = route('posts.index');
        $this->createUrl = route('posts.store');
    }
    /**
     * A basic feature test example.
     */
    public function test_posts_are_accessable(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get($this->indexUrl);

        $response->assertStatus(200);
    }

    public function test_posts_content()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();

        $expectedPostJson = [
            "id" => $post->id,
            "title" => $post->title,
            "text" => $post->text,
            "author_id" => $post->author_id,
            "is_draft" => $post->is_draft,
            "created_at" => $post->created_at,
            "updated_at" => $post->updated_at,
            "deleted_at" => $post->deleted_at
        ];

        $response = $this->actingAs($user)->get($this->indexUrl);

        $response->assertJsonFragment([$expectedPostJson]);
    }

    public function test_create_post_stored_in_db()
    {
        $post = Post::factory()->make();
        $user = User::factory()->create();

        $payload = [
            "title" => $post->title,
            "text" => $post->text,
            "is_draft" => $post->is_draft,
            "author_id" => $post->author_id,
        ];

        $response = $this->actingAs($user)->post($this->createUrl, $payload);

        $id = $response->json()['id'];

        // $this->assertDatabaseHas(Post::class, ['id' => $id]);
        // $this->assertDatabaseHas(Post::class, $payload);
        $this->assertNotNull(Post::find($id), 'Post is not found');

        // $response->assertJsonFragment([$expectedPostJson]);
    }
}
