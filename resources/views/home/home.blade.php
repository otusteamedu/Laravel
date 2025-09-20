@extends('layouts.main')

@php
$products = $response->data['products'];
@endphp

@section('content')
@vite('resources/views/home/js/index.js')
<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('account.dashboard') }}
    </h2>
</x-slot>
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <h1 class="mb-4 text-center">Что приготовить из того, что есть?</h1>
                <form id="recipe-form" action="{{ route('home.getRecipe') }}">
                    <div id="products-container">
                        <div class="row align-items-center mb-3 product-item p-3 rounded-3 shadow-sm border" id="product">
                            <!-- Выбор продукта -->
                            <div class="col-md-6">
                                <select class="form-select rounded-3" name="product_id">
                                    <option value="" disabled selected>Выберите продукт</option>
                                    @foreach($products as $product)
                                    <option value="{{ $product['id'] }}">{{ $product['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 d-flex gap-2">
                                <!-- Поле для ввода количества -->
                                <input type="number" class="form-control rounded-3" name="product_value" placeholder="Количество" min="0">
                                <!-- Select для выбора меры -->
                                <select class="form-select rounded-3" name="measure_id" data-route="{{ route('home.getMeasureByProduct', ['id' => 'int']) }}">
                                    <option value="" disabled selected>Выберите меру</option>
                                </select>
                            </div>
                            <!-- Кнопка удаления -->
                            <div class="col-md-2 d-flex">
                                <button type="button" class="btn btn-danger w-100t" id="remove-product">Удалить</button>
                            </div>
                        </div>
                    </div>

                    <button type="button" class="btn btn-secondary mb-3" id="add-product">Добавить продукт</button>

                    <div class="mb-3">
                        <label for="portions" class="form-label">Количество порций</label>
                        <input type="number" class="form-control rounded-3" id="portions" name="portions" min="1" value="1">
                    </div>

                    <button type="button" class="btn btn-primary" id="select-recipe">Подобрать рецепт</button>
                </form>
                <div id="recipes-result" class="mt-5"></div>
            </div>
        </div>
    </div>
</div>
@endsection