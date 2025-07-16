<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Group;
use Tests\TestCase;

#[Group('profile')]
class ProfileControllerTest extends TestCase
{
    use RefreshDatabase;
   
    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpTheTestEnvironment();
    }

    public function test_profile_page_is_displayed(): void
    {
        $user = User::factory()->create();
        $userId = $user->id;
        $userName = $user->name;
        $userEmail = $user->email;

        $url = route('profile.edit', $userId);
        $response = $this->actingAs($user)->get($url);

        $response->assertOk();
        $response->assertSee($userName);
        $response->assertSee($userEmail);
    }

    public function test_profile_information_can_be_updated(): void
    {
        $user = User::factory()->create();
        $userId = $user->id;

        $url = route('profile.update', $userId);
        $response = $this->actingAs($user)->put($url, [
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
        
        $redirectUrl = route('profile.edit', $userId);
        $response->assertRedirect($redirectUrl);
        $user->refresh();
        $this->assertSame('Test User', $user->name);
        $this->assertSame('test@example.com', $user->email);
    }

    public function test_password_can_be_updated(): void
    {
        $user = User::factory()->create();
        $userId = $user->id;

        $url = route('profile.password', $userId);
        $response = $this->actingAs($user)->put($url, [
            'password' => 'password1',
            'password_confirmation' => 'password1',
        ]);
        
        $redirectUrl = route('profile.edit', $userId);
        $response->assertRedirect($redirectUrl);
        $user->refresh();
        $this->assertTrue(password_verify('password1', $user->password));
    }

    public function test_history_page_is_displayed(): void
    {
        $user = User::factory()->create();
        $userId = $user->id;
        $order = Order::factory()->create(['user_id' => $userId]);
        $category = Category::factory()->create();
        $product1 = Product::factory()->create(['category_id' => $category->getId(), 'stock' => 10]);
        $product2 = Product::factory()->create(['category_id' => $category->getId(), 'stock' => 10]);
        $arr1 = ['order_id' => $order->getId(), 'product_id'=> $product1->getId(), 'paid_price' => $product1->getPrice(), 'count' => 2];
        $arr2 = ['order_id' => $order->getId(), 'product_id'=> $product2->getId(), 'paid_price' => $product2->getPrice(), 'count' => 2];
        OrderProduct::factory()->create($arr1);
        OrderProduct::factory()->create($arr2);
        $total = \Illuminate\Support\Number::format($order->getTotal(), locale: 'ru');

        $url = route('profile.history', $userId);
        $response = $this->actingAs($user)->get($url);

        $response->assertOk();
        $response->assertSee($order->getId());
        $response->assertSee($total);
        $response->assertSee($product1->getTitle());
        $response->assertSee($product2->getTitle());
    }
}
