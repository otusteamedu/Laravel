<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Group;
use Tests\TestCase;
use Illuminate\Support\Number;

#[Group('cart')]
class CartControllerTest extends TestCase
{
    use RefreshDatabase;
   
    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpTheTestEnvironment();
    }

    public function test_add_to_cart(): void
    {
        $category = Category::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->getId(), 'stock' => 10]);
        
        $url = route('cart.add', $product->getId());
        $response = $this->post($url);
        
        $redirectUrl = route('cart.index');
        $response->assertRedirect($redirectUrl);
        $cart = [];
        $cart[$product->getId()] = [
            'product' => $product->getTitle(),
            'quantity' => 1,
            'price' => $product->getPrice(),
        ];
        $response->assertSessionHas('cart', $cart);
    }

    public function test_remove_from_cart(): void
    {
        $category = Category::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->getId(), 'stock' => 10]);
        
        $url = route('cart.add', $product->getId());
        $response = $this->post($url);
        $url = route('cart.remove', $product->getId());
        $response = $this->delete($url);
        
        $redirectUrl = route('cart.index');
        $response->assertRedirect($redirectUrl);
        $response->assertSessionMissing('cart');
    }

    public function test_cart_is_displayed(): void
    {
        $category = Category::factory()->create();
        $product1 = Product::factory()->create(['category_id' => $category->getId(), 'stock' => 10]);
        $product2 = Product::factory()->create(['category_id' => $category->getId(), 'stock' => 10]);
        
        $url = route('cart.add', $product1->getId());
        $this->post($url);
        $url = route('cart.add', $product2->getId());
        $this->post($url);
        $url = route('cart.index');
        $response = $this->get($url);

        $response->assertOk();
        $response->assertSee($product1->getTitle());
        $response->assertSee(Number::format($product1->getPrice(), locale: 'ru'));
        $response->assertSee($product2->getTitle());
        $response->assertSee(Number::format($product2->getPrice(), locale: 'ru'));
    }

    public function test_clear_cart(): void
    {
        $category = Category::factory()->create();
        $product1 = Product::factory()->create(['category_id' => $category->getId(), 'stock' => 10]);
        $product2 = Product::factory()->create(['category_id' => $category->getId(), 'stock' => 10]);
        
        $url = route('cart.add', $product1->getId());
        $this->post($url);
        $url = route('cart.add', $product2->getId());
        $this->post($url);
        $url = route('cart.clear');
        $response = $this->post($url);
        
        $redirectUrl = route('cart.index');
        $response->assertRedirect($redirectUrl);
        $response->assertSessionMissing('cart');
    }
}
