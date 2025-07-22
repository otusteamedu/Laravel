{{-- resources/views/admin/apartments/create.blade.php --}}

<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Добавить новую квартиру') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded p-6">
                <form method="POST" action="{{ route('admin.apartments.store') }}">
                    @csrf

                    <div class="mb-4">
                        <label for="owner" class="block text-sm font-medium text-gray-700">Владелец</label>
                        <input
                            type="text"
                            name="owner"
                            id="owner"
                            value="{{ old('owner') }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >
                        @error('owner')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="serial_number" class="block text-sm font-medium text-gray-700">Серийный номер</label>
                        <input
                            type="text"
                            name="serial_number"
                            id="serial_number"
                            value="{{ old('serial_number') }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >
                        @error('serial_number')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center space-x-4">
                        <button
                            type="submit"
                            class="inline-flex justify-center rounded-md border border-transparent bg-green-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2"
                        >
                            Создать
                        </button>

                        <a
                            href="{{ route('admin.apartments.index') }}"
                            class="inline-flex justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                        >
                            Отмена
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
