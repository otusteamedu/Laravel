<?php

namespace App\Infrastructure\Eloquent\Repositories;

use App\Domain\Cart\Model\Cart;
use App\Domain\Cart\Model\CartItem;
use App\Domain\Cart\Repositories\CartRepositoryInterface;
use App\Infrastructure\Eloquent\Models\Cart as EloquentCart;
use App\Infrastructure\Eloquent\Models\CartItem as EloquentCartItem;

class CartRepository implements CartRepositoryInterface
{
    public function findById(string $id): ?Cart
    {
        $model = EloquentCart::find($id);
        return $model ? $this->toEntity($model) : null;
    }

    public function findByUserId(int $userId): ?Cart
    {
        $model = EloquentCart::where('user_id', $userId)
            ->first();

        return $model ? $this->toEntity($model) : null;
    }

    public function findByGuestToken(string $guestToken): ?Cart
    {

        $model = EloquentCart::where('guest_token', $guestToken)
            ->where('expires_at', '>', now())
            ->first();


        return $model ? $this->toEntity($model) : null;
    }

    public function save(Cart $cart): Cart
    {
        $data = [
            'user_id' => $cart->getUserId(),
            'guest_token' => $cart->getGuestToken(),
            'expires_at' => $cart->getExpiresAt(),
        ];

        if ($cart->getId()) {
            EloquentCart::where('id', $cart->getId())->update($data);
            $model = EloquentCart::find($cart->getId());
        } else {
            $model = EloquentCart::create($data);
        }

        // Save cart items
        $this->saveCartItems($cart, $model);

        $model->load('items');

        return $this->toEntity($model);
    }

    private function saveCartItems(Cart $cart, EloquentCart $model): void
    {
        $currentItemIds = [];

        foreach ($cart->getItems() as $item) {

            $itemData = [
                'product_id' => $item->getProductId(),
                'quantity' => $item->getQuantity(),
                'price' => $item->getPrice()
            ];

            if ($item->getId()) {
                EloquentCartItem::where('id', $item->getId())->update($itemData);
                $currentItemIds[] = $item->getId();
            } else {
                $newItem = $model->items()->create($itemData);
                $currentItemIds[] = $newItem->id;
            }
        }

        // Remove items that are no longer in the cart
        $model->items()->whereNotIn('id', $currentItemIds)->delete();
    }

    public function delete(string $cartId): void
    {
        EloquentCart::destroy($cartId);
    }

    public function cleanupExpired(): void
    {
        EloquentCart::where('expires_at', '<=', now())->delete();
    }

    private function toEntity(EloquentCart $model): Cart
    {

        $items = $model->items->map(function ($itemModel) use ($model) {

            return new CartItem(
                $itemModel->id,
                $itemModel->product_id,
                $itemModel->price,
                $itemModel->quantity,
                $itemModel->created_at,
                $itemModel->updated_at
            );
        })->toArray();

        return new Cart(
            $model->id,
            $model->user_id,
            $model->guest_token,
            $items,
            $model->expires_at,
            $model->created_at,
            $model->updated_at
        );
    }
}
