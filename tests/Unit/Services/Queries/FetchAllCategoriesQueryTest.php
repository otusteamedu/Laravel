<?php
namespace Tests\Unit\Services\Queries;

use Tests\TestCase;
use App\Services\Queries\FetchAllCategories\Query;
use App\Services\Queries\FetchAllCategories\Fetcher;
use App\Services\DTO\Categories\CategoryDTO;
use App\Models\Category;
use App\Repositories\Categories\CategoryRepositoryInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;

class FetchAllCategoriesQueryTest extends TestCase {
    use RefreshDatabase;
    public function test_query_can_be_created_from_page()
    {
        $query = Query::fromPage(3, 20);

        $this->assertEquals(20, $query->limit);
        $this->assertEquals(40, $query->offset); // (3-1) * 20 = 40
    }
    public function test_query_uses_default_values()
    {
        $query = new Query();

        $this->assertEquals(10, $query->limit);
        $this->assertEquals(0, $query->offset);
    }
}
