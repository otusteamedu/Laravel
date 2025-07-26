<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Ivan\ExportExcelProducts\Services\ExportExcel;
use Maatwebsite\Excel\Facades\Excel;

class ExportProductExcel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:export-product-excel
                                {mode : type of export 1(group category) or 2 (only product)}
                                {--H|headers=*  : list products fields export}
                                {--C|categories=*  : list categories ids  export}
                                ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Export products to excel file';


    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle(ExportExcel $excel)
    {
        $mode = (int) $this->argument('mode');
        if (!in_array($mode, [1,2])) {
            $this->error('Invalid mode. Please use 1 for grouped by category or 2 for only products.');
            return self::FAILURE;
        }

        $availableColumns = [
            'id' => 'ID', 'title' => 'Название', 'alias' => 'Псевдоним', 'text' => 'Описание',
            'image' => 'Изображение', 'is_sale' => 'На Распродаже', 'published' => 'Опубликовано',
            'order' => 'Порядок', 'price' => 'Цена', 'user_id' => 'ID Пользователя',
            'created_at' => 'Создано', 'updated_at' => 'Обновлено',
        ];

        $headers = $this->option('headers');
        if (empty($headers)) {
            $headers = config('export_excel_products.default_column', ['id', 'title', 'price']);
        }

        // Validate headers
        $invalidHeaders = array_diff($headers, array_keys($availableColumns));
        if (!empty($invalidHeaders)) {
            $this->warn('The following headers are invalid and will be ignored: ' . implode(', ', $invalidHeaders));
            $headers = array_intersect($headers, array_keys($availableColumns));
        }

        // Check if any valid headers are left
        if(empty($headers)){
            $this->error('No valid headers were provided for export.');
            return self::FAILURE;
        }

        $this->info('Starting product export...');

        $this->table($headers, []);

        $categories = $this->option('categories');

        $fileName = 'public/exports/products_export_' . date('Y_m_d_H_i_s') . '.xlsx';

        //$path = Storage::disk('public')->path($fileName);

        if($mode == 2){
            $excel->saveProductsToFile($headers, $categories, $fileName);
        }else{
            $excel->saveProductsGroupByCategoriesToFile($headers, $categories, $fileName);
        }

        $this->info('Products saved to excel file successfully at: ' . $fileName);

        return self::SUCCESS;

    }
}
