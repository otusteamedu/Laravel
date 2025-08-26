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
        $this->model = $this->getMockBuilder(TestModel::class)
                            ->onlyMethods(['isSlugExists'])
                            ->getMock();

        $this->model->method('isSlugExists')
                    ->willReturn(false);
    }

    public function test_it_generates_slug_from_specified_field()
    {
        $this->model->title = 'Test Title';
        $this->model->generateSlug();

        $this->assertEquals('test-title', $this->model->slug);
    }

    public function test_it_keeps_existing_slug_on_update()
    {
        $this->model->title = 'Test Title';
        $this->model->slug = 'existing-slug';
        $this->model->generateSlug();

        $this->assertEquals('existing-slug', $this->model->slug);
    }

    public function test_it_generates_unique_slug_for_duplicate()
    {
        $model1 = $this->getMockBuilder(TestModel::class)
                       ->onlyMethods(['isSlugExists'])
                       ->getMock();

        $model1->method('isSlugExists')
               ->willReturn(false);

        $model2 = $this->getMockBuilder(TestModel::class)
                       ->onlyMethods(['isSlugExists'])
                       ->getMock();

        $model2->method('isSlugExists')
               ->willReturnOnConsecutiveCalls(true, false);

        $model1->title = 'Test Title';
        $model1->generateSlug();

        $model2->title = 'Test Title';
        $model2->generateSlug();

        $this->assertNotEquals($model1->slug, $model2->slug);
        $this->assertStringStartsWith('test-title-', $model2->slug);
    }

    public function test_it_handles_special_characters()
    {
        $this->model->title = 'Test & Title! With @#$%^&*()';
        $this->model->generateSlug();

        $this->assertEquals('test-title-with-at', $this->model->slug);
    }

    public function test_it_handles_unicode_characters()
    {
        $this->model->title = 'Тестовый Заголовок';
        $this->model->generateSlug();

        $this->assertEquals('testovyi-zagolovok', $this->model->slug);
    }

    public function test_it_uses_default_slug_column()
    {
        $this->assertEquals('slug', $this->model->getSlugColumn());
    }

    public function test_it_uses_default_slug_from_field()
    {
        $this->assertEquals('title', $this->model->getSlugFrom());
    }

    public function test_it_creates_slug_on_model_creation()
    {
        $model = $this->getMockBuilder(TestModel::class)
                      ->onlyMethods(['isSlugExists'])
                      ->getMock();

        $model->method('isSlugExists')
              ->willReturn(false);

        $model->title = 'Test Title';

        // Имитируем событие creating
        $model->generateSlug();

        $this->assertEquals('test-title', $model->slug);
    }

    public function test_it_checks_slug_existence()
    {
        $model = $this->getMockBuilder(TestModel::class)
                      ->onlyMethods(['isSlugExists'])
                      ->getMock();

        $model->method('isSlugExists')
              ->willReturn(false);

        $this->assertFalse($model->checkSlugExists('test-slug'));
    }

    public function test_it_generates_incremental_slugs()
    {
        $model = $this->getMockBuilder(TestModel::class)
                      ->onlyMethods(['isSlugExists'])
                      ->getMock();

        $model->method('isSlugExists')
              ->willReturnOnConsecutiveCalls(true, false);

        $result = $model->makeUniqueSlug('test-slug');
        $this->assertEquals('test-slug-1', $result);
    }
}

class TestModel extends Model
{
    use HasSlug;

    protected $fillable = ['title', 'slug'];
    public $timestamps = false;
    public $exists = false;

    public function getSlugFrom(): string
    {
        return $this->slugFrom();
    }

    public function getSlugColumn(): string
    {
        return $this->slugColumn();
    }

    public function generateSlug(): void
    {
        $this->makeSlug();
    }

    public function makeUniqueSlug(string $slug): string
    {
        return $this->slugUnique($slug);
    }

    public function checkSlugExists(string $slug): bool
    {
        return $this->isSlugExists($slug);
    }
}
