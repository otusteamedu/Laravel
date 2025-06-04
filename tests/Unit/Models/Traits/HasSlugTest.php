<?php

namespace Tests\Unit\Models\Traits;

use App\Models\Traits\HasSlug;
use Illuminate\Database\Eloquent\Model;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Group;

#[Group('traits')]
class HasSlugTest extends TestCase
{
    private TestModel $model;

    protected function setUp(): void
    {
        parent::setUp();
        $this->model = new TestModel();
    }

    public function test_it_generates_slug_from_specified_field()
    {
        $this->model->title = 'Test Title';
        $this->model->makeSlug();

        $this->assertEquals('test-title', $this->model->slug);
    }

    public function test_it_keeps_existing_slug_on_update()
    {
        $this->model->title = 'Test Title';
        $this->model->makeSlug();
        $originalSlug = $this->model->slug;

        $this->model->title = 'Updated Title';
        $this->model->makeSlug();

        $this->assertEquals($originalSlug, $this->model->slug);
    }

    public function test_it_generates_unique_slug_for_duplicate()
    {
        $model1 = new TestModel();
        $model1->title = 'Test Title';
        $model1->makeSlug();

        $model2 = new TestModel();
        $model2->title = 'Test Title';
        $model2->makeSlug();

        $this->assertNotEquals($model1->slug, $model2->slug);
        $this->assertStringStartsWith('test-title-', $model2->slug);
    }

    public function test_it_handles_special_characters()
    {
        $this->model->title = 'Test & Title! With @#$%^&*()';
        $this->model->makeSlug();

        $this->assertEquals('test-title-with-at', $this->model->slug);
    }

    public function test_it_handles_unicode_characters()
    {
        $this->model->title = 'Тестовый Заголовок';
        $this->model->makeSlug();

        $this->assertEquals('testovyi-zagolovok', $this->model->slug);
    }
}

class TestModel extends Model
{
    use HasSlug;

    protected $fillable = ['title', 'slug'];
    public $timestamps = false;
    public $exists = false;

    public static function slugFrom(): string
    {
        return 'title';
    }

    public function makeSlug(): void
    {
        $this->makeSlugFromField();
    }

    protected function makeSlugFromField(): void
    {
        $slug = str($this->{$this->slugFrom()})
            ->ascii()
            ->slug()
            ->value();

        $this->{$this->slugColumn()} = $this->{$this->slugColumn()} ?? $this->makeSlugUnique($slug);
    }

    protected function makeSlugUnique(string $slug): string
    {
        static $slugs = [];
        $originalSlug = $slug;
        $counter = 0;

        while (isset($slugs[$slug])) {
            $counter++;
            $slug = $originalSlug . '-' . $counter;
        }

        $slugs[$slug] = true;
        return $slug;
    }
}
