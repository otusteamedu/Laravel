<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use App\Domain\Apartment\Apartment;
use App\Models\ApartmentCharge;
use App\Models\ApartmentCounter;
use App\Models\ApartmentDetail;
use App\Models\ApartmentFee;

class ClearCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * Пример:
     * sail artisan clear:cache apartments
     */
    protected $signature = 'clear:cache 
                            {entity? : Сущность для очистки кэша, например: apartments}';

    protected $description = 'Очистка кэша для указанных сущностей приложения';

    private array $entities = [
        'apartments'         => Apartment::class,
        'apartment_charges'  => ApartmentCharge::class,
        'apartment_counters' => ApartmentCounter::class,
        'apartment_details'  => ApartmentDetail::class,
        'apartment_fees'     => ApartmentFee::class,
    ];

    public function handle(): int
    {
        $entity = $this->argument('entity');

        if ($entity === null) {
            $this->warn('No entity specified. Clearing cache for all entities.');
            foreach (array_keys($this->entities) as $entityName) {
                $this->clearEntityCache($entityName);
            }
        } else {
            if (!array_key_exists($entity, $this->entities)) {
                $this->error("Unknown entity: {$entity}");
                return Command::FAILURE;
            }
            $this->clearEntityCache($entity);
        }

        $this->info('Cache clearing completed successfully!');
        return Command::SUCCESS;
    }

    private function clearEntityCache(string $entity): void
    {
        Cache::tags([$entity])->flush();
        $this->info("Cache cleared for {$entity}");
    }
}
