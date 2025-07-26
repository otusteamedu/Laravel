<?php
namespace Ivan\ExportExcelProducts\Services\Contracts;

interface ExportExcelInterface
{
    public function saveProducts(array $selectedColumns, array $selectedCategories);
    public function saveProductsGroupByCategories(array $selectedColumns, array $selectedCategories);

    public function saveProductsToFile(array $selectedColumns, array $selectedCategories, string $filename);
    public function saveProductsGroupByCategoriesToFile(array $selectedColumns, array $selectedCategories, string $filename);
}
