<?php
namespace Ivan\ExportExcelProducts;

use Ivan\ExportExcelProducts\Services\Contracts\ExportExcelInterface;
use Ivan\ExportExcelProducts\Services\ExportExcel;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function register(){
        $this->app->singleton(ExportExcelInterface::class, ExportExcel::class);
    }

    public function boot(){


        $this->mergeConfigFrom(__DIR__.'/../config/export_excel_products.php', 'export_excel_products');
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        $this->loadViewsFrom(__DIR__.'/../views', 'export_excel_products');


        $this->publishes([
            __DIR__.'/../config/export_excel_products.php' => config_path('export_excel_products.php'),
        ], 'export_excel_products');

        $this->publishes([
            __DIR__.'/../views' => resource_path('views/vendor/export_excel_products'),
        ], 'export_excel_products');

    }
}
