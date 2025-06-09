<?php

namespace Tests\Feature;

use App\Models\Blog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Group;
use Tests\TestCase;

#[Group('blogs')]
class BlogControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $indexUrl;

    protected $createUrl;

    protected function setUp(): void
    {
        $this->setUpTheTestEnvironment();
        $this->indexUrl = route('blogs.index');
        $this->createUrl = route('blogs.store');
    }

    /**
     * A basic feature test example.
     */
    // public function test_blogs_are_accessable(): void
    // {
    //     $user = User::factory()->create();

    //     $response = $this->get($this->indexUrl);

    //     $response->assertStatus(200);
    // }

    // public function test_blogs_content()
    // {
    //     $blog = Blog::factory()->create();

    //     $expectedBlogJson = [
    //         'title' => $blog->title,
    //         'preview' => $blog->preview,
    //         'text' => $blog->text,
    //         'created_at' => $blog->created_at,
    //         'updated_at' => $blog->updated_at,
    //     ];

    //     $response = $this->get($this->indexUrl);

    //     $response->assertJsonFragment([$expectedBlogJson]);
    // }

    public function test_create_blog_stored_in_db()
    {
        $blog = Blog::factory()->make();

        $payload = [
            'title' => $blog->title,
            'preview' => $blog->preview,
            'text' => $blog->text,
            'created_at' => $blog->created_at,
            'updated_at' => $blog->updated_at,
        ];

        $response = $this->post($this->createUrl, $payload);

        $id = $response->json()['id'];

        // $this->assertDatabaseHas(Post::class, ['id' => $id]);
        // $this->assertDatabaseHas(Post::class, $payload);
        $this->assertNotNull(Blog::find($id), 'Post is not found');

        // $response->assertJsonFragment([$expectedPostJson]);
    }
}
