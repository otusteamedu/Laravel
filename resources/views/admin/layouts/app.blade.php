{{-- resources/views/admin/layouts/app.blade.php --}}

    <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - @yield('title', 'Dashboard')</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal">

<div class="flex h-screen bg-gray-200">
    <div class="w-64 bg-gray-800 text-white flex flex-col">
        <div class="p-4 text-2xl font-semibold border-b border-gray-700">
            Admin Dashboard
        </div>
        <nav class="flex-1 p-4">
            <a href="{{ route('admin.products.index') }}" class="block py-2 px-4 rounded hover:bg-gray-700 mb-2">Products</a>
            <a href="{{ route('admin.categories.index') }}" class="block py-2 px-4 rounded hover:bg-gray-700">Categories</a> {{-- Added --}}
            {{-- Add more navigation links here --}}
        </nav>
    </div>

    <div class="flex-1 flex flex-col overflow-hidden">
        <header class="flex justify-between items-center bg-white p-4 shadow-md">
            <div class="text-xl font-semibold">@yield('title')</div>
        </header>

        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <strong class="font-bold">Success!</strong>
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <strong class="font-bold">Whoops!</strong>
                    <span class="block sm:inline">There were some problems with your input.</span>
                    <ul class="mt-2 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</div>

</body>
</html>
