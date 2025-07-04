@extends('admin.layouts.app')

@section('title', 'Edit Category: ' . $category->title)

@section('content')
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Edit Category: <span class="text-blue-600">{{ $category->title }}</span></h1>

    <div class="bg-white shadow-md rounded-lg p-6">
        <form action="{{ route('admin.categories.update', $category) }}" method="POST">
            @csrf
            @method('PUT')

            @include('admin.categories.form')

            <div class="flex items-center justify-between mt-6">
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded shadow">
                    Update Category
                </button>
                <a href="{{ route('admin.categories.index') }}" class="text-gray-600 hover:text-gray-900">Cancel</a>
            </div>
        </form>
    </div>
@endsection
