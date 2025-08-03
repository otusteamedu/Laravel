<?php

namespace App\Jobs;

use App\DTO\ProductPriceData;
use App\Models\User;
use App\Notifications\ProductPriceChangedNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use App\Repositories\Contracts\ProductRepositoryInterface;

class SendPriceChangeNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $productId;
    public float $oldPrice;
    public float $newPrice;
    protected ProductRepositoryInterface $productRepository;

    /**
     * Create a new job instance.
     */
    public function __construct(ProductPriceData $priceData,)
    {
        $this->productId = $priceData->productId;
        $this->oldPrice = $priceData->oldPrice;
        $this->newPrice = $priceData->newPrice;
        $this->productRepository = app(ProductRepositoryInterface::class);
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

            $product = $this->productRepository->find($this->productId);;

            foreach ($users as $user) {
                $user->notify(new ProductPriceChangedNotification(
                    $this->productId,
                    $this->oldPrice,
                    $this->newPrice,
                    $product->title,
                    $product->alias
                ));
            }


            Log::info('Price change notifications sent', [
                'product_id' => $this->productId,
                'product_title' => $product->title,
                'old_price' => $this->oldPrice,
                'new_price' => $this->newPrice,
                'users_notified' => $users->count()
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to send price change notifications', [
                'product_id' => $this->productId,
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
            'product_id' => $this->productId,
            'error' => $exception->getMessage()
        ]);
    }
}
