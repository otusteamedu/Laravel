@extends('admin.layouts.app')

@section('title', 'Edit Product: ' . $product->title)

@section('content')
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Edit Product: <span class="text-blue-600">{{ $product->title }}</span></h1>

    <div class="bg-white shadow-md rounded-lg p-6">
        <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            @include('admin.products.form')

            <div class="flex items-center justify-between mt-6">
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded shadow">
                    Update Product
                </button>
                <a href="{{ route('admin.products.index') }}" class="text-gray-600 hover:text-gray-900">Cancel</a>
            </div>
        </form>
    </div>
@endsection
