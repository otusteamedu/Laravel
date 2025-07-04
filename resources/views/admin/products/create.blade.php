@extends('admin.layouts.app')

@section('title', 'Create New Product')

@section('content')
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Create New Product</h1>

    <div class="bg-white shadow-md rounded-lg p-6">
        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            @include('admin.products.form')

            <div class="flex items-center justify-between mt-6">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow">
                    Create Product
                </button>
                <a href="{{ route('admin.products.index') }}" class="text-gray-600 hover:text-gray-900">Cancel</a>
            </div>
        </form>
    </div>
@endsection
