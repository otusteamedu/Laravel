{{-- resources/views/admin/apartments/index.blade.php --}}

<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Список квартир') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="mb-4">
                <a href="{{ route('admin.apartments.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                    Добавить новую квартиру
                </a>
            </div>

            <div class="bg-white shadow rounded overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">ID</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Владелец</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Серийный номер</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Действие</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($apartments as $apartment)
                            <tr>
                                <td class="px-4 py-2">{{ $apartment->id }}</td>
                                <td class="px-4 py-2">{{ $apartment->owner }}</td>
                                <td class="px-4 py-2">{{ $apartment->serial_number }}</td>
                                <td class="px-4 py-2">
                                    <a href="{{ route('admin.apartments.edit', $apartment) }}"
                                       class="text-blue-600 hover:underline">
                                        Редактировать
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-4 text-center text-gray-500">
                                    Квартиры не найдены.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
