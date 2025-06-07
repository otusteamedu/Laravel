<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Создание команды
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="flex items-center justify-center">
                    <form method="post" action="{{ route('teams.store') }}" class="p-8 rounded w-1/2" enctype="multipart/form-data">
                        @csrf
                        @include('teams.partials.form-fields')
                        <x-primary-button @class(['mt-2'])>Создать</x-primary-button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
