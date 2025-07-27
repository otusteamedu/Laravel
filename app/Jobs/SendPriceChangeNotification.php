<?php

namespace App\Jobs;

use App\Events\ProductPriceChanged;
use App\Models\Product;
use App\Models\User;
use App\Notifications\ProductPriceChangedNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendPriceChangeNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public Product $product;
    public float $oldPrice;
    public float $newPrice;

    /**
     * Create a new job instance.
     */
    public function __construct(Product $product, float $oldPrice, float $newPrice)
    {
        $this->product = $product;
        $this->oldPrice = $oldPrice;
        $this->newPrice = $newPrice;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            // Получаем всех пользователей, которым нужно отправить уведомление
            // Можно добавить дополнительную логику для фильтрации пользователей
            $users = User::where('email_verified_at', '!=', null)
                ->whereHas('notifications_settings', function($query) {
                    $query->where('price_changes', true);
                })
                ->orWhereDoesntHave('notifications_settings')
                ->get();

            foreach ($users as $user) {
                $user->notify(new ProductPriceChangedNotification(
                    $this->product,
                    $this->oldPrice,
                    $this->newPrice
                ));
            }

            Log::info('Price change notifications sent', [
                'product_id' => $this->product->id,
                'product_title' => $this->product->title,
                'old_price' => $this->oldPrice,
                'new_price' => $this->newPrice,
                'users_notified' => $users->count()
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to send price change notifications', [
                'product_id' => $this->product->id,
                'error' => $e->getMessage()
            ]);

            throw $e;
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('Price change notification job failed', [
            'product_id' => $this->product->id,
            'error' => $exception->getMessage()
        ]);
    }
}
