<?php
namespace App\Services;

use App\Models\Product;
use App\Exceptions\StockIsEmptyException;
use App\Repositories\ProductsRepository;

class CartService
{
    public function __construct(
        private ProductsRepository $repository,
    ) {}
    public function getCart(): array
    {
        return session()->get('cart', []);
    }

    public function calculateTotal(array $cart): int
    {
        $total = 0;
        
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        
        return $total;
    }

    public function clearCart(): void
    {
        session()->forget('cart');
    }

    public function add(int $productId): void
    {
        $cart = session()->get('cart', []);

        $product = $this->repository->findShort($productId);
        $stock = $product->getStock();

        $count = $cart[$productId]['quantity'] ?? 0;
        $count++;

        if ($count > $stock) {
            throw new StockIsEmptyException();
        }
        
        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] = $count;
        } else {
            $cart[$productId] = [
                'product' => $product->getTitle(),
                'quantity' => 1,
                'price' => $product->getPrice(),
            ];
        }
        
        session()->put('cart', $cart);
    }

    public function remove(int $productId): void
    {
        $cart = session()->get('cart', []);
        
        if (isset($cart[$productId])) {
            if ($cart[$productId]['quantity'] > 1) {
                $cart[$productId]['quantity']--;
            } else {
                unset($cart[$productId]);
            }
        }

        if (empty($cart)) {
            session()->forget('cart');
        } else {
            session()->put('cart', $cart);
        }
    }
}