<?php

namespace Ivan\ExportExcelProducts\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use App\Models\Category;
use Ivan\ExportExcelProducts\Exports\Sheets\CategoryProductsSheet;

class ProductsExport implements WithMultipleSheets
{
    use Exportable;

    private $selectedColumns;
    private $selectedCategoriesIds;

    public function __construct(array $selectedColumns = [], array $selectedCategoriesIds = [])
    {
        $this->selectedColumns = $selectedColumns;
        $this->selectedCategoriesIds = $selectedCategoriesIds;
    }

    /**
     * @return array
     */
    public function sheets(): array
    {
        $sheets = [];

        // Получаем все категории или только выбранные
        $categories = Category::query();
        if (!empty($this->selectedCategoriesIds)) {
            $categories->whereIn('id', $this->selectedCategoriesIds);
        }
        $categories = $categories->get();

        // Для каждой категории создаем отдельную вкладку с продуктами этой категории
        foreach ($categories as $category) {
            // Передаем выбранные столбцы в конструктор CategoryProductsSheet
            $sheets[] = new CategoryProductsSheet($category, $this->selectedColumns);
        }

        return $sheets;
    }
}
