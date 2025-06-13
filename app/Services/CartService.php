<?php
namespace App\Services;

use App\Exceptions\StockIsEmptyException;
use App\Repositories\ZoneRepository;
use App\Repositories\ProductsRepository;

class CartService
{
    public function __construct(
        private ProductsRepository $repository,
        private ZoneRepository $zoneRepository,
    ) {}
    
    public function getCart(): array
    {
        return session()->get('cart', []);
    }

    public function getAddress(): string
    {
        $delivery =  session()->get('delivery', []);
        $address = $delivery['address'] ?? '';
        return $address;
    }

    public function getDeliveryPrice(): string
    {
        $delivery =  session()->get('delivery', []);
        $price = $delivery['zonePrice'] ?? 0;
        return $price;
    }

    public function getLat(): string
    {
        $delivery =  session()->get('delivery', []);
        $lat= $delivery['lat'] ?? '';
        return $lat;
    }
    public function getLon(): string
    {
        $delivery =  session()->get('delivery', []);
        $lon = $delivery['lon'] ?? '';
        return $lon;
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
        session()->forget('delivery');
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
            session()->forget('delivery');
        } else {
            session()->put('cart', $cart);
        }
    }

    public function setDelivery(array $data): void
    {
        
        $lat = $data['lat'];
        $lon = $data['lon'];
        $zoneData = $this->zoneRepository->fetchZone($lon, $lat);
        $zoneTitle = $zoneData['title'] ?? '';
        $zonePrice = $zoneData['price'] ?? 0;

        $delivery = [
            'lat' => $data['lat'],
            'lon' => $data['lon'],
            'address' => $data['address'],
            'zoneTitle' => $zoneTitle,
            'zonePrice' => $zonePrice,
        ];
        session()->put('delivery', $delivery);
    }

    public function unsetDelivery(): void{
        session()->forget('delivery');
    }

    public function checkCartNotEmpty(): bool
    {
        return session()->has('cart');
    }
}