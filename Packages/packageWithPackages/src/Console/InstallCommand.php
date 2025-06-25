<?php

namespace My\PackageWithPackages\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use My\PackageWithPackages\Http\Middleware\PackHeaders;
use Illuminate\Support\Str;
use My\PackageWithPackages\MyPackServiceProvider;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description;

    public function __construct()
    {
        $this->signature = 'myPackage:install';
        parent::__construct();
        $this->description = __('packageWithPackages::commands.install.description');
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {

        //выполняем миграции
        Artisan::call('migrate');
        $this->info(__('packageWithPackages::commands.install.migrationGood'));

        //создаем и выполняем сидер
        copy(
            __DIR__.'/../../database/seeders/MyPackageHeadSeeder.php',
            base_path('database/seeders/MyPackageHeadSeeder.php')
        );

        $path = base_path('database/seeders/MyPackageHeadSeeder.php');
        $search = 'namespace My\Database\Seeders;';
        $replace = 'namespace Database\Seeders;';
        file_put_contents($path, str_replace($search, $replace, file_get_contents($path)));

        Artisan::call('db:seed --class=MyPackageHeadSeeder');
        $this->info(__('packageWithPackages::commands.install.seederGood'));

        //добавляем посредников из пакета
        $aliases = ['packHeaders' => 'My\PackageWithPackages\Http\Middleware\PackHeaders::class'];
        $bootstrapApp = file_get_contents(base_path('bootstrap/app.php')); //!!!!!!

        $result = 0;
        $aliases = collect($aliases)
            ->filter(fn ($alias) => ! Str::contains($bootstrapApp, $alias))
            ->whenNotEmpty(function ($aliases) use ($bootstrapApp, &$result) {
                $aliases = $aliases->map(fn ($name, $alias) => "'$alias' => $name")->implode(','.PHP_EOL.'            ');

                $findStrPattern = strstr($bootstrapApp, '$middleware->alias('
                    .PHP_EOL.'            [');

                if ($findStrPattern) {
                    $bootstrapApp = str_replace(
                        '$middleware->alias('
                        .PHP_EOL.'            [',
                        '$middleware->alias('
                        .PHP_EOL.'            ['
                        .PHP_EOL.''
                        .PHP_EOL."                $aliases,"
                        .PHP_EOL,
                        $bootstrapApp,
                    );
                    file_put_contents(base_path('bootstrap/app.php'), $bootstrapApp); //!!!!!
                    $result = 1;
                } else {
                    $result = 2;
                }
            });
        if ($aliases->count() == 0) {
            $result = 3;
        }

        switch ($result) {
            case 1: $this->info(__('packageWithPackages::commands.install.middlewareAddGood')); break;
            case 2: $this->error(__('packageWithPackages::commands.install.canNotModifyBootstrapAppFile')); break;
            case 3: $this->warn(__('packageWithPackages::commands.install.middlewareAlreadyExists')); break;
            default: $this->error(__('packageWithPackages::commands.install.middlewareError')); break;
        }

        //публикуем конфиги
        Artisan::call('vendor:publish', ['--provider' => MyPackServiceProvider::class]);
        $this->info(__('packageWithPackages::commands.install.publishGood'));

    }
}
