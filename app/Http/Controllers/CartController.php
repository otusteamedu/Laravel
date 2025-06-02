<?php

namespace App\Http\Controllers;

use App\Exceptions\StockIsEmptyException;
use App\Http\Controllers\Controller;
use App\Services\CartService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class CartController extends Controller
{
    public function __construct(
        private CartService $service
    ) {}

    public function index(): View
    {
        $cart = $this->service->getCart();
        $total = $this->service->calculateTotal($cart);
        
        return view('cabinet.cart', compact('cart', 'total'));
    }

    public function add(int $productId): RedirectResponse
    {
        try {
            $this->service->add($productId);
        } catch (StockIsEmptyException $e) {
            return redirect()->route('cart.index')->with('error', 'Товара недостаточно для добавления в корзину');
        }
        
        return redirect()->route('cart.index')->with('success', 'Товар добавлен в корзину');
    }

    public function remove(int $productId)
    {
        $this->service->remove($productId);
        
        return redirect()->route('cart.index')->with('success', 'Товар удален из корзины');
    }

    public function clear()
    {
        $this->service->clearCart();
        
        return redirect()->route('cart.index')->with('success', 'Корзина очищена');
    }
}
