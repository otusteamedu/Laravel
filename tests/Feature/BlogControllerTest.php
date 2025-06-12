<?php

namespace Tests\Feature;

use App\Models\Blog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Group;
use Tests\TestCase;

#[Group('blogs')]
class BlogControllerTest extends TestCase
{
    use RefreshDatabase;
    // use WithoutExceptionHandling;

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
    public function test_blogs_content()
    {
        $blog = Blog::factory()->create();

        $response = $this->get($this->indexUrl);
        $response->assertJsonStructure([['id', 'title', 'preview', 'text', 'created_at']]);

    }

    public function test_server_error()
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
        $id = $response->json();
        // dump($id);

        $response->assertServerError(Blog::find($id), 'Server Error!');

    }

    public function test_service_unavailable()
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
        $id = $response->json();

        $response->assertServiceUnavailable(Blog::find($id), 'Service is unavailable!');

    }
}
