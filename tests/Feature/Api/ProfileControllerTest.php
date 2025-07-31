<?php

namespace Tests\Feature\Api;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use PHPUnit\Framework\Attributes\Group;
use Tests\TestCase;
use Laravel\Passport\ClientRepository;
use Illuminate\Support\Collection;

#[Group('api_profile')]
class ProfileControllerTest extends TestCase
{
    use RefreshDatabase;

    private $accessToken;
    private $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpTheTestEnvironment();

        $clientRepository = new ClientRepository();
        $client = $clientRepository->createPasswordGrantClient('Test Client', 'users');
        $this->user = User::factory()->create();

        $params = [
            'grant_type' => 'password',
            'client_id' => $client->id,
            'username' => $this->user->email,
            'password' => 'password',
            'scope' => '',
        ];
        $response = $this->postJson('/oauth/token', $params);
        $content = json_decode($response->getContent(), true);
        $this->accessToken = $content['access_token'];
    }

    public function test_profile_can_be_obtained(): void
    {
        $response = $this
            ->withHeaders(['Authorization' => 'Bearer ' . $this->accessToken])
            ->getJson('/api/v3/profile');

        $response->assertOk();
        $response->assertJson(fn(AssertableJson $json) =>
            $json->hasAll(['id', 'name', 'email', 'email_verified_at', 'role_id', 'role_title', 'created_at', 'updated_at'])
        );
        $response->assertJsonPath('name', $this->user->name);
        $response->assertJsonPath('email', $this->user->email);
        $response->assertJsonPath('role_id', $this->user->role_id);
    }

    public function test_profile_can_be_updated(): void
    {
        $newName = 'Anna';

        $response = $this
            ->withHeaders(['Authorization' => 'Bearer ' . $this->accessToken])
            ->patchJson('/api/v3/profile', ['name' => $newName]);


        $response->assertOk();
        $response->assertJson(fn(AssertableJson $json) =>
            $json->hasAll(['id', 'name', 'email', 'email_verified_at', 'role_id', 'role_title', 'created_at', 'updated_at'])
        );
        $response->assertJsonPath('name', $newName);
    }

    public function test_password_can_be_updated(): void
    {
        $newPassword = 'password1';

        $response = $this
            ->withHeaders(['Authorization' => 'Bearer ' . $this->accessToken])
            ->patchJson('/api/v3/profile/password', [
                'password' => $newPassword,
                'password_confirmation' => $newPassword,
            ]);


        $response->assertOk();
        $response->assertJson(fn(AssertableJson $json) =>
            $json->hasAll(['id', 'name', 'email', 'email_verified_at', 'role_id', 'role_title', 'created_at', 'updated_at'])
        );
    }

    public function test_orders_can_be_obtained(): void
    {
        $category = Category::factory()->create();
        $brand = Brand::factory()->create();
        $product1 = Product::factory()->create(['category_id' => $category->id, 'brand_id' => $brand->id]);
        $product2 = Product::factory()->create(['category_id' => $category->id, 'brand_id' => $brand->id]);

        $count1 = 2;
        $count2 = 1;
        $paidPrice1 = 10000;
        $paidPrice2 = 20000;
        $order = Order::factory()->create(['user_id' => $this->user->id]);
        $order->products()->sync([
            $product1->id => ['count' => $count1, 'paid_price' => $paidPrice1],
            $product2->id => ['count' => $count2, 'paid_price' => $paidPrice2]
        ]);

        $response = $this
            ->withHeaders(['Authorization' => 'Bearer ' . $this->accessToken])
            ->getJson('/api/v3/profile/orders');

        $response->assertOk();
        $response->assertJson(fn(AssertableJson $json) => 
            $json->has(1)
                ->first(fn (AssertableJson $js1) => 
                    $js1->where('products', fn (Collection $col) => $col->count() == 2)->etc()
                )
        );
        $response->assertJsonPath('0.user_id', $this->user->id);
        $response->assertJsonPath('0.total', $order->getTotal());
        $response->assertJsonPath('0.products.0.title', $product1->title);
        $response->assertJsonPath('0.products.0.count', $count1);
        $response->assertJsonPath('0.products.0.paid_price', $paidPrice1);
        $response->assertJsonPath('0.products.1.title', $product2->title);
        $response->assertJsonPath('0.products.1.count', $count2);
        $response->assertJsonPath('0.products.1.paid_price', $paidPrice2);
    }
}
