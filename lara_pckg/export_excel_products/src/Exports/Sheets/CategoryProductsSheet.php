<?php

namespace Ivan\ExportExcelProducts\Exports\Sheets;

use App\Models\Product;
use App\Models\Category;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class CategoryProductsSheet implements FromCollection, WithHeadings, ShouldAutoSize, WithMapping, WithTitle
{
    private Category $category;
    private array $selectedColumns;

    public function __construct(Category $category, array $selectedColumns = [])
    {
        $this->category = $category;
        $this->selectedColumns = $selectedColumns;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Product::whereHas('categories', function ($query) {
            $query->where('categories.id', $this->category->id);
        })->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        // Заголовки для столбцов продуктов, основанные на выбранных столбцах
        $allHeadings = [
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

        $headings = [];
        foreach ($this->selectedColumns as $column) {
            if (isset($allHeadings[$column])) {
                $headings[] = $allHeadings[$column];
            }
        }
        return $headings;
    }

    /**
     * @param mixed $product
     * @return array
     */
    public function map($product): array
    {
        // Форматирует данные для каждой строки продукта, основанные на выбранных столбцах.
        $rowData = [];
        foreach ($this->selectedColumns as $column) {
            $value = $product->{$column};

            switch ($column) {
                case 'images': // Если вы хотите включить все изображения, а не только основное
                    $images = json_decode($value, true);
                    $allImages = $product->image;
                    if (!empty($images)) {
                        $allImages .= ' | ' . implode(' | ', $images);
                    }
                    $rowData[] = $allImages;
                    break;
                case 'is_sale':
                case 'published':
                    $rowData[] = $value ? 'Да' : 'Нет';
                    break;
                case 'created_at':
                case 'updated_at':
                    $rowData[] = $value ? $value->format('Y-m-d H:i:s') : '';
                    break;
                case 'price':
                    $rowData[] = number_format($value, 2, '.', '');
                    break;
                default:
                    $rowData[] = $value;
                    break;
            }
        }
        return $rowData;
    }

    /**
     * @return string
     */
    public function title(): string
    {
        // Название вкладки будет названием категории
        // Ограничиваем длину названия вкладки до 31 символа, так как это ограничение Excel
        return substr($this->category->title, 0, 31);
    }
}
