<?php

namespace Ivan\ExportExcelProducts\Controllers;

use App\Models\Category;
use Ivan\ExportExcelProducts\Services\ExportExcel;
use Illuminate\Http\Request;


class ExportController
{
    public function form()
    {
        // Определите все доступные столбцы для экспорта
        $availableColumns = [
            'id' => 'ID',
            'title' => 'Название',
            'alias' => 'Псевдоним',
            'text' => 'Описание',
            'image' => 'Изображение',
            'is_sale' => 'На Распродаже',
            'published' => 'Опубликовано',
            'order' => 'Порядок',
            'price' => 'Цена',
            'user_id' => 'ID Пользователя',
            'created_at' => 'Создано',
            'updated_at' => 'Обновлено',
        ];

        // Получаем все категории для выбора
        $categories = Category::all();

        return view('export_excel_products::form', compact('availableColumns', 'categories'));
    }

    public function save(Request $request, ExportExcel $excel)
    {

        // Получаем выбранные столбцы из запроса
        $selectedColumns = $request->input('columns', []);
        // Получаем выбранные ID категорий из запроса
        $selectedCategories = $request->input('categories', []);

        $mode = $request->input('export_mode', 'category_sheets');


        // Если столбцы не выбраны
        if (empty($selectedColumns)) {
            $selectedColumns = config('export_excel_products.default_column', ['id', 'title', 'price']);
        }

        if($mode === 'single_sheet'){
            return $excel->saveProducts($selectedColumns, $selectedCategories);
        }

        return $excel->saveProductsGroupByCategories($selectedColumns, $selectedCategories);
    }
}
