@php use App\Services\Team\TeamData; @endphp
@php
    /** @var TeamData $team */
@endphp
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Команды
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden shadow-sm sm:rounded-lg">
                <x-link href="{{ route('teams.create') }}" @class(['my-2', 'mx-2'])>Создать</x-link>
                @if (session('status') === 'team-created')
                    <x-alert @class(['text-gray-600', 'bg-indigo-50'])>
                        Команда успешно добавлена
                    </x-alert>
                @endif

                @if (session('status') === 'team-deleted')
                    <x-alert @class(['text-white', 'bg-red-600'])>
                        Команда успешно удалена
                    </x-alert>
                @endif

                @if (session('status') === 'team-not-deleted')
                    <x-alert @class(['text-white', 'bg-red-500'])>
                        {{ implode(';', $errors->get('error')) }}
                    </x-alert>
                @endif

                @if (session('status') === 'team-updated')
                    <x-alert @class(['text-gray-600', 'bg-indigo-50'])>
                        Команда успешно обновлена
                    </x-alert>
                @endif

                @foreach($teams as $team)
                    <div class="mx-auto bg-white rounded-lg shadow-md overflow-hidden mb-2">
                        <div class="p-4">
                            <h2 class="text-xl font-semibold mb-2">{{$team->getNickname()}} ({{ $team->getName() }})</h2>
                            <div class="flex space-x-4">
                                <!-- Просмотр -->
                                <a href="{{ route('teams.show', $team->getId()) }}"
                                   class="text-blue-500 hover:text-blue-700"
                                   title="Просмотр">
                                    <!-- Иконка глаз (View) -->
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                         viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </a>

                                <!-- Редактирование -->
                                <a href="{{ route('teams.edit', $team->getId()) }}"
                                   class="text-green-500 hover:text-green-700"
                                   title="Редактировать">
                                    <!-- Иконка карандаша (Edit) -->
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                         viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M11 4H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2v-5" />
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z" />
                                    </svg>
                                </a>

                                <!-- Удаление -->
                                <form action="{{ route('teams.destroy', $team->getId()) }}" method="POST"
                                      onsubmit="return confirm('Вы уверены, что хотите удалить?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="text-red-500 hover:text-red-700"
                                            title="Удалить">
                                        <!-- Иконка корзины (Delete) -->
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                             viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>
