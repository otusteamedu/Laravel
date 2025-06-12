<?php

namespace Tests\Unit\Models;

use App\Models\Category;
use App\Models\News;
use App\Models\Traits\HasSlug;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use PHPUnit\Framework\Attributes\Group;
use Tests\TestCase;

#[Group("category-model")]
class CategoryTest extends TestCase
{
    private Category $category;

    protected function setUp(): void
    {
        parent::setUp();

        $this->category = $this->getMockBuilder(Category::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['newCollection', 'getRelationValue'])
            ->getMock();

        $this->category->method('newCollection')
            ->willReturn(new Collection());

        $this->category->method('getRelationValue')
            ->willReturn(new Collection());
    }

    public function test_fillable_attributes()
    {
        $category = new Category();
        $this->assertEquals(['name', 'sort', 'slug'], $category->getFillable());
    }

    public function test_timestamps_disabled()
    {
        $category = new Category();
        $this->assertFalse($category->timestamps);
    }

    public function test_default_sort_value()
    {
        $category = new Category();
        $this->assertEquals(1, $category->getAttributes()['sort']);
    }

    public function test_slug_from_returns_name()
    {
        $this->assertEquals('name', Category::slugFrom());
    }

    public function test_news_relation()
    {
        $category = new Category();
        $relation = $category->news();

        $this->assertInstanceOf(HasMany::class, $relation);
        $this->assertInstanceOf(News::class, $relation->getRelated());
    }

    public function test_it_uses_has_slug_trait()
    {
        $this->assertContains(HasSlug::class, class_uses_recursive(Category::class));
    }

    public function test_it_has_news_relationship()
    {
        $category = new Category();
        $newsRelation = $category->news();

        $this->assertInstanceOf(HasMany::class, $newsRelation);
        $this->assertEquals('category_id', $newsRelation->getForeignKeyName());
        $this->assertEquals('id', $newsRelation->getLocalKeyName());
        $this->assertInstanceOf(News::class, $newsRelation->getRelated());
    }

    public function test_it_can_access_news_collection()
    {
        $newsCollection = $this->category->news;

        $this->assertInstanceOf(Collection::class, $newsCollection);
        $this->assertCount(0, $newsCollection);
    }

    public function test_it_can_set_attributes()
    {
        $category = new Category();
        $category->name = 'Test Category';
        $category->sort = 1;
        $category->slug = 'test-category';

        $this->assertEquals('Test Category', $category->name);
        $this->assertEquals(1, $category->sort);
        $this->assertEquals('test-category', $category->slug);
    }
}
