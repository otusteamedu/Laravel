<?php
namespace Tests\Unit\Categories\Factory;

use Tests\TestCase;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoryFactoryTest extends TestCase
{
    use RefreshDatabase;
    public function test_factory_creates_category_with_correct_fields()
    {
        $category = Category::factory()->create();

        $this->assertNotNull($category->name);
        $this->assertNotNull($category->color);
        $this->assertNotNull($category->description);

        // Проверяем что цвет в правильном формате
        $this->assertMatchesRegularExpression('/^#[0-9a-fA-F]{6}$/', $category->color);
    }

    public function test_factory_creates_category_with_data()
    {
        $category = Category::factory()->create([
                                                    'name' => 'Специальная категория',
                                                    'color' => '#123456'
                                                ]);

        $this->assertEquals('Специальная категория', $category->name);
        $this->assertEquals('#123456', $category->color);
    }

    public function test_factory_creates_some_category()
    {
        $categories = Category::factory()->count(5)->create();

        $this->assertCount(5, $categories);

        // Проверяем что все названия разные
        $names = $categories->pluck('name')->toArray();
        $this->assertEquals(5, count(array_unique($names)));
    }
}
