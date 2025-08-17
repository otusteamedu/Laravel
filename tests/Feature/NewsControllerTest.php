<?php

namespace Tests\Feature;

use App\Models\News;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\Group;
use Tests\TestCase;

#[Group("news")]
class NewsControllerTest extends TestCase
{
    use RefreshDatabase;
    protected $indexUrl;
    protected $createUrl;

    protected function setUp(): void
    {
        $this->setUpTheTestEnvironment();
        $this->indexUrl = route('news.index');
        $this->createUrl = route('news.create');
    }
    /**
     * A basic feature test example.
     */
    public function test_news_are_accessable(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get($this->indexUrl);

        $response->assertStatus(200);
    }

    public function test_news_content()
    {
        $user = User::factory()->create();
        $news = News::factory()->create();

        $expectedPostJson = [
            "id" => $news->id,
            "user_id" => $news->user_id,
            "name" => $news->name,
            "link"=>$news->link,
            "preview" => $news->preview,
            "photo" => $news->photo,
            "create_at" => $news->create_at,
            "text" => $news->text,
            "created_at" => null,
            "updated_at" => null,
            "deleted_at" => null
        ];
        $response = $this->json('GET', '/e/one-all');
        $response->assertStatus(200)
        ->assertJsonFragment([$expectedPostJson]);
    }

    public function test_create_news_stored_in_db()
    {
        $news = News::factory()->make();
        $user = User::factory()->create();

        $payload = [
            "name" => $news->name,
            "text" => $news->text,
            "preview" => $news->preview,
            "link"=>$news->link,
            "photo" => $news->photo,
            "user_id" => $news->user_id
        ];

        $response = $this->actingAs($user)->post($this->createUrl, $payload);
        dd($response->json());
        $id = $response->json()['id'];
        $this->assertNotNull(News::find($id), 'News is not found');
    }
}
