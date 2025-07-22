<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Список настроек') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-4 rounded bg-green-100 p-4 text-green-800">
                    {{ session('success') }}
                </div>
            @endif

            <div class="overflow-x-auto bg-white shadow rounded-lg">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-800 text-white">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Месяц</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Срок оплаты</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Дата списания</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Счёт</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Оплатить до</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Действие</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @foreach($settings as $setting)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $setting->id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $setting->month_name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $setting->month_to_pay }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $setting->month_to_date }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $setting->bill }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $setting->pay_up_to }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('admin.settings.edit', $setting) }}"
                                       class="text-indigo-600 hover:text-indigo-900">
                                        Редактировать
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</x-app-layout>
