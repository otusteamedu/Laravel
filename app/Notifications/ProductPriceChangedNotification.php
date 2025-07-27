<?php

namespace App\Notifications;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ProductPriceChangedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public Product $product;
    public float $oldPrice;
    public float $newPrice;

    /**
     * Create a new notification instance.
     */
    public function __construct(Product $product, float $oldPrice, float $newPrice)
    {
        $this->product = $product;
        $this->oldPrice = $oldPrice;
        $this->newPrice = $newPrice;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $priceChange = $this->newPrice > $this->oldPrice ? 'увеличилась' : 'снизилась';
        $priceChangeIcon = $this->newPrice > $this->oldPrice ? '📈' : '📉';

        return (new MailMessage)
            ->subject("Изменение цены на товар: {$this->product->title}")
            ->greeting("Здравствуйте, {$notifiable->name}!")
            ->line("Цена на товар \"{$this->product->title}\" {$priceChange} {$priceChangeIcon}")
            ->line("Старая цена: {$this->formatPrice($this->oldPrice)}")
            ->line("Новая цена: {$this->formatPrice($this->newPrice)}")
            ->action('Посмотреть товар', url("/products/{$this->product->alias}"))
            ->line('Спасибо за использование нашего сервиса!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'product_id' => $this->product->id,
            'product_title' => $this->product->title,
            'product_alias' => $this->product->alias,
            'old_price' => $this->oldPrice,
            'new_price' => $this->newPrice,
            'price_change_type' => $this->newPrice > $this->oldPrice ? 'increase' : 'decrease',
            'price_difference' => abs($this->newPrice - $this->oldPrice),
        ];
    }

    /**
     * Format price for display
     */
    private function formatPrice(float $price): string
    {
        return number_format($price, 2, ',', ' ') . ' ₽';
    }
}
