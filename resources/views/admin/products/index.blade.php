@extends('admin.layouts.app')

@section('title', 'Manage Products')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Products</h1>
        <a href="{{ route('admin.products.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow">Add New Product</a>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <table class="min-w-full leading-normal">
            <thead>
            <tr>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                    ID
                </th>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                    Title
                </th>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                    Price
                </th>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                    Published
                </th>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                    Order
                </th>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100"></th>
            </tr>
            </thead>
            <tbody>
            @forelse ($products as $product)
                <tr>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">{{ $product->id }}</td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">{{ $product->title }}</td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">${{ number_format($product->price, 2) }}</td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $product->published ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $product->published ? 'Yes' : 'No' }}
                            </span>
                    </td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">{{ $product->order }}</td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-right">
                        <a href="{{ route('admin.products.edit', $product) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                        <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this product?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-center">No products found.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
        <div class="p-4">
            {{ $products->links() }}
        </div>
    </div>
@endsection
