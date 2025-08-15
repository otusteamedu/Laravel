<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MigrateLast extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:last';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Получить данные последней миграции';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $migrateion = DB::table('migrations')
            ->orderBy('id', 'desc')
            ->first();

        $this->info(sprintf('id:%d,migration:%s,batch:%d', $migrateion->id, $migrateion->migration, $migrateion->batch));

        return self::SUCCESS;
    }
}
