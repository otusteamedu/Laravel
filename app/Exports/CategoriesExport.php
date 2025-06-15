<?php

namespace App\Exports;

use App\Services\CategoriesService;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class CategoriesExport implements FromView
{
    public function __construct(private CategoriesService $service) 
    {}

    public function view(): View
    {
        $categories = $this->service->getAll();
        return view('exports.categories', compact('categories'));
    }
}
