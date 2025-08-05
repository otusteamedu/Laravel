@extends('layouts.main')

@section('content')
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
                    <form id="recipe-form">
                        <div id="ingredients-list" class="mb-3">
                            <div class="row g-2 ingredient-input">
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="ingredient_name[]" placeholder="Название продукта">
                                </div>
                                <div class="col-md-4">
                                    <input type="number" class="form-control" name="ingredient_amount[]" placeholder="Граммовка (г)">
                                </div>
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-danger w-100 remove-ingredient">Удалить</button>
                                </div>
                            </div>
                        </div>

                        <button type="button" class="btn btn-secondary mb-3" id="add-ingredient">Добавить продукт</button>

                        <div class="mb-3">
                            <label for="portions" class="form-label">Количество порций</label>
                            <input type="number" class="form-control" id="portions" name="portions" min="1" value="1">
                        </div>

                        <button type="submit" class="btn btn-primary">Подобрать рецепт</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
