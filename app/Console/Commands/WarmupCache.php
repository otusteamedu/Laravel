<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use App\Models\Apartment;
use App\Models\ApartmentCharge;
use App\Models\ApartmentCounter;
use App\Models\ApartmentDetail;
use App\Models\ApartmentFee;

class WarmupCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * Пример:
     * sail artisan warmup:cache apartments --clear --limit=10
     */
    protected $signature = 'warmup:cache 
                            {entity? : Сущность для кэширования например: apartments} 
                            {--clear : Очистить кэш перед прогревом} 
                            {--limit= : Ограничить количество записей для кэширования}';

    
    protected $description = 'Прогрев кэша для основных сущностей приложения';

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
        $clear = $this->option('clear');
        $limit = $this->option('limit');

        // Если сущность не указана — кэшируем все
        if ($entity === null) {
            $this->warn('No entity specified. Warming up cache for all entities.');
            foreach (array_keys($this->entities) as $entityName) {
                $this->processEntity($entityName, $clear, $limit);
            }
        } else {
            if (!array_key_exists($entity, $this->entities)) {
                $this->error("Unknown entity: {$entity}");
                return Command::FAILURE;
            }
            $this->processEntity($entity, $clear, $limit);
        }

        $this->info('Cache warmup completed successfully!');
        return Command::SUCCESS;
    }

    private function processEntity(string $entity, bool $clear, ?string $limit): void
    {
        if ($clear) {
            Cache::tags([$entity])->flush();
            $this->info("Cache cleared for {$entity}");
        }

        $modelClass = $this->entities[$entity];
        $query = $modelClass::query();

        if ($limit !== null) {
            $query->limit((int) $limit);
        }

        $records = $query->get();

        foreach ($records as $record) {
            $cacheKey = "{$entity}_{$record->id}";
            Cache::put($cacheKey, $record->toArray(), 3600);
        }

        $count = $records->count();
        $this->info("Warmed up cache for {$entity} ({$count} records)");
    }
}
