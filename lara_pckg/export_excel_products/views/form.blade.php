{{-- Example include admin layout
@extends('admin.layouts.app')
@section('title', 'Export Products')
@section('content')
    --}}

<div class="bg-gradient-to-br from-purple-50 to-indigo-100 min-h-screen flex items-center justify-center p-4">
<div class="bg-white rounded-xl shadow-2xl p-8 md:p-10 w-full max-w-2xl">
    <h1 class="text-3xl font-extrabold text-indigo-800 mb-6 text-center">
        Настройки экспорта продуктов в Excel
    </h1>

    <form action="{{ route('export_excel_products.save') }}" method="POST">
        @csrf

        <!-- Выбор режима экспорта -->
        <div class="mb-8">
            <h2 class="text-xl font-semibold text-gray-800 mb-4 border-b pb-2">
                Выберите режим экспорта:
            </h2>
            <div class="flex flex-col sm:flex-row gap-4">
                <label class="flex items-center text-gray-700 cursor-pointer">
                    <input
                        type="radio"
                        name="export_mode"
                        value="category_sheets"
                        class="form-radio h-5 w-5 text-indigo-600 focus:ring-indigo-500"
                        checked
                    >
                    <span class="ml-2 text-lg">Разбивать по категориям (отдельные вкладки)</span>
                </label>
                <label class="flex items-center text-gray-700 cursor-pointer">
                    <input
                        type="radio"
                        name="export_mode"
                            value="single_sheet"
                        class="form-radio h-5 w-5 text-indigo-600 focus:ring-indigo-500"
                    >
                    <span class="ml-2 text-lg">Все товары на одной вкладке (с колонкой категорий)</span>
                </label>
            </div>
        </div>
        <!-- Выбор столбцов -->
        <div class="mb-8">
            <h2 class="text-xl font-semibold text-gray-800 mb-4 border-b pb-2">
                Выберите столбцы для экспорта:
            </h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                @foreach($availableColumns as $columnKey => $columnName)
                    <label class="flex items-center text-gray-700 cursor-pointer">
                        <input
                            type="checkbox"
                            name="columns[]"
                            value="{{ $columnKey }}"
                            class="form-checkbox h-5 w-5 text-indigo-600 rounded focus:ring-indigo-500"
                            checked>
                        <span class="ml-2 text-lg">{{ $columnName }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        <!-- Выбор категорий -->
        <div class="mb-8">
            <h2 class="text-xl font-semibold text-gray-800 mb-4 border-b pb-2">
                Выберите категории для экспорта:
            </h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 max-h-60 overflow-y-auto pr-2">
                @forelse($categories as $category)
                    <label class="flex items-center text-gray-700 cursor-pointer">
                        <input
                            type="checkbox"
                            name="categories[]"
                            value="{{ $category->id }}"
                            class="form-checkbox h-5 w-5 text-green-600 rounded focus:ring-green-500"
                            checked >
                        <span class="ml-2 text-lg">{{ $category->title }}</span>
                    </label>
                @empty
                    <p class="text-gray-500 col-span-full">Категории не найдены.</p>
                @endforelse
            </div>
        </div>

        <!-- Кнопка экспорта -->
        <div class="text-center mt-6">
            <button
                type="submit"
                class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-8 rounded-lg shadow-lg transition-all duration-300 ease-in-out transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-opacity-75"
            >
                Экспортировать в Excel
            </button>
        </div>
    </form>
</div>
</div>
{{--@endsection--}}
