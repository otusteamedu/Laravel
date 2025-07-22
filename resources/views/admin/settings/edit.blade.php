{{-- resources/views/admin/settings/edit.blade.php --}}

<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Редактировать настройку #') }}{{ $setting->id }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded p-6">
                <form method="POST" action="{{ route('admin.settings.update', $setting) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label for="month_name" class="block text-sm font-medium text-gray-700">Месяц</label>
                        <input
                            type="text"
                            name="month_name"
                            id="month_name"
                            value="{{ old('month_name', $setting->month_name) }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >
                        @error('month_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="month_to_pay" class="block text-sm font-medium text-gray-700">Срок оплаты</label>
                        <input
                            type="text"
                            name="month_to_pay"
                            id="month_to_pay"
                            value="{{ old('month_to_pay', $setting->month_to_pay) }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >
                        @error('month_to_pay')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="month_to_date" class="block text-sm font-medium text-gray-700">Дата списания</label>
                        <input
                            type="text"
                            name="month_to_date"
                            id="month_to_date"
                            value="{{ old('month_to_date', $setting->month_to_date) }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >
                        @error('month_to_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="bill" class="block text-sm font-medium text-gray-700">Счёт</label>
                        <input
                            type="text"
                            name="bill"
                            id="bill"
                            value="{{ old('bill', $setting->bill) }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >
                        @error('bill')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="pay_up_to" class="block text-sm font-medium text-gray-700">Оплатить до</label>
                        <input
                            type="text"
                            name="pay_up_to"
                            id="pay_up_to"
                            value="{{ old('pay_up_to', $setting->pay_up_to) }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >
                        @error('pay_up_to')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center space-x-4">
                        <button
                            type="submit"
                            class="inline-flex justify-center rounded-md border border-transparent bg-green-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2"
                        >
                            Сохранить
                        </button>

                        <a
                            href="{{ route('admin.settings.index') }}"
                            class="inline-flex justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                        >
                            Назад
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
