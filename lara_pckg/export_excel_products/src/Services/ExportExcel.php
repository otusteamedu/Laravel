<?php

namespace Ivan\ExportExcelProducts\Services;

use Ivan\ExportExcelProducts\Exports\ProductsExport;
use Ivan\ExportExcelProducts\Services\Contracts\ExportExcelInterface;
use Maatwebsite\Excel\Facades\Excel;

class ExportExcel implements ExportExcelInterface
{
    public function saveProducts($selectedColumns, $selectedCategories){
        // TO DO
        return Excel::download(new ProductsExport($selectedColumns, $selectedCategories), 'products_by_categories.xlsx');
    }
    public function saveProductsGroupByCategories($selectedColumns, $selectedCategories){

        return Excel::download(new ProductsExport($selectedColumns, $selectedCategories), 'products_by_categories.xlsx');
    }
}
