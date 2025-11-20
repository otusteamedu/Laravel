<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Создать пост
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('posts.store') }}" class="form" method="post">
                        @csrf
                        <fieldset class="fieldset">
                            <legend class="fieldset-legend">Заголовок</legend>
                            <input type="text" name="title" class="input block w-full" placeholder="Заголовок" />
                        </fieldset>
                        <fieldset class="fieldset mb-4">
                            <legend class="fieldset-legend">Текст</legend>
                            <textarea class="textarea w-full" name="text"></textarea>
                        </fieldset>
                        <button class="btn btn-primary">Создать</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
