@extends('admin.layouts.app')

@section('title', 'Create New Category')

@section('content')
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Create New Category</h1>

    <div class="bg-white shadow-md rounded-lg p-6">
        <form action="{{ route('admin.categories.store') }}" method="POST">
            @csrf

            @include('admin.categories.form')

            <div class="flex items-center justify-between mt-6">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow">
                    Create Category
                </button>
                <a href="{{ route('admin.categories.index') }}" class="text-gray-600 hover:text-gray-900">Cancel</a>
            </div>
        </form>
    </div>
@endsection
