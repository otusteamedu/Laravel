<?php

namespace Tests\Feature\Admin;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Group;
use Tests\TestCase;
use Illuminate\Support\Number;

#[Group('order')]
class OrderControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpTheTestEnvironment();
    }

    public function test_order_list()
    {
        $user = User::factory()->create(['role_id' => 2]);
        $user1 = User::factory()->create(['name' => 'Bob']);
        $user2 = User::factory()->create(['name' => 'Alice']);
        $user3 = User::factory()->create(['name' => 'Cantana']);
        Order::factory()->create(['user_id' => $user1->id]);
        Order::factory()->create(['user_id' => $user2->id]);
        Order::factory()->create(['user_id' => $user3->id]);
        
        $url = route('admin.orders.index') . '?sort=user&direction=asc';
        $response = $this->actingAs($user)->get($url);

        $response->assertOk();
        $response->assertViewIs('admin.orders.index');
        $response->assertViewHas('orders', function ($orders) {
            $condition1 = $orders[0]->getUser()->name === 'Alice';
            $condition2 = $orders[1]->getUser()->name === 'Bob';
            $condition3 = $orders[2]->getUser()->name === 'Cantana';
            return $condition1 && $condition2 && $condition3;
        });
        $response->assertSee('Alice');
        $response->assertSee('Bob');
        $response->assertSee('Cantana');
    }

    public function test_order_show()
    {
        $user = User::factory()->create(['role_id' => 2]);
        $user1 = User::factory()->create(['name' => 'Bob']);
        $order = Order::factory()->create(['user_id'=> $user1->id]);
        $category = Category::factory()->create();
        $product1 = Product::factory()->create(['category_id' => $category->getId()]);
        $product2 = Product::factory()->create(['category_id' => $category->getId()]); 
        OrderProduct::factory()->create([
            'order_id' => $order->getId(),
            'product_id' => $product1->getId(),
            'paid_price' => $product1->getPrice(),
            'count' => 1,
        ]);
        OrderProduct::factory()->create([
            'order_id' => $order->getId(),
            'product_id' => $product2->getId(),
            'paid_price' => $product2->getPrice(),
            'count' => 1,
        ]);

        $url = route('admin.orders.show', $order->getId());
        $response = $this->actingAs($user)->get($url);

        $response->assertOk();
        $response->assertViewIs('admin.orders.show');
        $response->assertViewHas('orderId', $order->getId());
        $response->assertViewHas('clientName', $user1->name);
        $response->assertViewHas('clientEmail', $user1->email);
        $response->assertViewHas('products');
        $response->assertViewHas('total');
        $response->assertViewHas('createdAt');
        $response->assertSee($user1->name);
        $response->assertSee($user1->email);
        $response->assertSee($product1->getTitle());
        $response->assertSee(Number::format($product1->getPrice(), locale: 'ru'));
        $response->assertSee($product2->getTitle());
        $response->assertSee(Number::format($product2->getPrice(), locale: 'ru'));

        $url = route('admin.orders.show', 100);
        $response = $this->actingAs($user)->get($url);

        $response->assertNotFound();
    }

    public function test_order_create()
    {
        $user = User::factory()->create(['role_id' => 2]);

        $url = route('admin.orders.create');
        $response = $this->actingAs($user)->get($url);

        $response->assertOk();
        $response->assertViewIs('admin.orders.create');
        $response->assertViewHas('users');
        $response->assertViewHas('products');
        $response->assertSee('Клиент');
        $response->assertSee('Товары');
    }

    public function test_order_store()
    {
        $user = User::factory()->create(['role_id' => 2]);
        $user1 = User::factory()->create();
        $category = Category::factory()->create();
        $product1 = Product::factory()->create(['category_id' => $category->getId()]);
        $product2 = Product::factory()->create(['category_id' => $category->getId()]);
        $data = [
            'user_id' => $user1->id,
            'product_id' => [$product1->getId(), $product2->getId()],
            'count' => [1, 1],
        ];

        $url = route('admin.orders.store');
        $response = $this->actingAs($user)->post($url, $data);

        $redirectUrl = route('admin.orders.index');
        $response->assertRedirect($redirectUrl);
        $this->assertDatabaseHas('orders', ['user_id' => $user1->id]);
        $this->assertDatabaseHas('order_products', [
            'order_id' => Order::where('user_id', $user1->id)->first()->getId(),
            'product_id' => $product1->getId(),
            'paid_price' => $product1->getPrice(),
            'count' => 1,
        ]);
        $this->assertDatabaseHas('order_products', [
            'order_id' => Order::where('user_id', $user1->id)->first()->getId(),
            'product_id' => $product2->getId(),
            'paid_price' => $product2->getPrice(),
            'count' => 1,
        ]);
    }

    public function test_order_edit()
    {
        $user = User::factory()->create(['role_id' => 2]);
        $user1 = User::factory()->create();
        $order = Order::factory()->create(['user_id'=> $user1->id]);
        $category = Category::factory()->create();
        $product1 = Product::factory()->create(['category_id' => $category->getId()]);
        $product2 = Product::factory()->create(['category_id' => $category->getId()]); 
        OrderProduct::factory()->create([
            'order_id' => $order->getId(),
            'product_id' => $product1->getId(),
            'paid_price' => $product1->getPrice(),
            'count' => 1,
        ]);
        OrderProduct::factory()->create([
            'order_id' => $order->getId(),
            'product_id' => $product2->getId(),
            'paid_price' => $product2->getPrice(),
            'count' => 1,
        ]);

        $url = route('admin.orders.edit', $order->getId());
        $response = $this->actingAs($user)->get($url);

        $response->assertOk();
        $response->assertViewIs('admin.orders.edit');
        $response->assertViewHas('orderId', $order->getId());
        $response->assertViewHas('userId', $user1->id);
        $response->assertViewHas('orderProducts');
        $response->assertViewHas('users');
        $response->assertViewHas('products');
        $response->assertSee($user1->name);
        $response->assertSee($product1->getTitle());
        $response->assertSee($product2->getTitle());

        $url = route('admin.orders.edit', 100);
        $response = $this->actingAs($user)->get($url);

        $response->assertNotFound();
    }

    public function test_order_update()
    {
        $user = User::factory()->create(['role_id' => 2]);
        $user1 = User::factory()->create();
        $order = Order::factory()->create(['user_id'=> $user1->id]);
        $category = Category::factory()->create();
        $product1 = Product::factory()->create(['category_id' => $category->getId()]);
        $product2 = Product::factory()->create(['category_id' => $category->getId()]); 
        OrderProduct::factory()->create([
            'order_id' => $order->getId(),
            'product_id' => $product1->getId(),
            'paid_price' => $product1->getPrice(),
            'count' => 1,
        ]);
        $newData = [
            'user_id' => $user1->id,
            'product_id' => [$product2->getId()],
            'count' => [1],
        ];

        $url = route('admin.orders.update', $order->getId());
        $response = $this->actingAs($user)->put($url, $newData);

        $redirectUrl = route('admin.orders.index');
        $response->assertRedirect($redirectUrl);
        $this->assertDatabaseHas('orders', ['id' => $order->getId(), 'user_id' => $user1->id]);
        $this->assertDatabaseHas('order_products', [
            'order_id' => $order->getId(), 
            'product_id' => $product2->getId(), 
            'paid_price' => $product2->getPrice(), 
            'count' => 1
        ]);
        $this->assertDatabaseMissing('order_products', [
            'order_id' => $order->getId(), 
            'product_id' => $product1->getId(), 
        ]);

        $url = route('admin.orders.update', 100);
        $response = $this->actingAs($user)->put($url, $newData);

        $response->assertNotFound();
    }

    public function test_order_destroy()
    {
        $user = User::factory()->create(['role_id' => 2]);
        $user1 = User::factory()->create();
        $order = Order::factory()->create(['user_id'=> $user1->id]);
        $category = Category::factory()->create();
        $product1 = Product::factory()->create(['category_id' => $category->getId()]);
        OrderProduct::factory()->create([
            'order_id' => $order->getId(),
            'product_id' => $product1->getId(),
            'paid_price' => $product1->getPrice(),
            'count' => 1,
        ]);

        $url = route('admin.orders.destroy', $order->getId());
        $response = $this->actingAs($user)->delete($url);

        $redirectUrl = route('admin.orders.index');
        $response->assertRedirect($redirectUrl);
        $this->assertDatabaseMissing('orders', ['id' => $order->getId()]);
        $this->assertDatabaseMissing('order_products', [
            'order_id' => $order->getId(), 
            'product_id' => $product1->getId(), 
        ]);

        $url = route('admin.orders.destroy', 100);
        $response = $this->actingAs($user)->delete($url);

        $response->assertNotFound();
    }
}
