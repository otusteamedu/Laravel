<!-- resources/views/components/header.blade.php -->

<header class="bg-gray-800 text-white shadow">
    <div class="max-w-7xl mx-auto px-4 flex items-center justify-between h-16">
        <!-- Левая часть: меню -->
        <nav class="flex space-x-4">
            <a href="{{ route('dashboard') }}" class="hover:bg-gray-700 px-3 py-2 rounded">{{ __('account.dashboard') }}</a>
            <a href="{{ route('fibonachi') }}" class="hover:bg-gray-700 px-3 py-2 rounded">{{ __('account.fibonachi') }}</a>
            <a href="{{ route('area.index') }}" class="hover:bg-gray-700 px-3 py-2 rounded">{{ __('account.area') }}</a>
            <!-- добавьте свои разделы -->
        </nav>

        <!-- Правая часть: кнопка авторизации / профиль -->
        <div x-data="{ open: false }" class="relative flex items-center">
            @auth
                <!-- Если пользователь авторизован -->
                <button @click="open = !open" class="flex items-center space-x-2 bg-gray-700 hover:bg-gray-600 px-3 py-2 rounded focus:outline-none">
                    <span>{{ Auth::user()->name }}</span>
                    <!-- Иконка стрелки -->
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <!-- Выпадающее меню -->
                <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white text-black rounded shadow-lg z-50">
                    <a href="{{ route('profile.edit') }}" class="block px-4 py-2 hover:bg-gray-100">Профиль</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-2 hover:bg-gray-100">Выйти</button>
                    </form>
                </div>
            @else
                <!-- Если пользователь не авторизован -->
                <a href="{{ route('login') }}" class="bg-blue-600 hover:bg-blue-500 px-4 py-2 rounded">Авторизация</a>
            @endauth
        </div>
    </div>
</header>

<!-- Для работы Alpine.js, добавьте его в ваш layout, если еще не подключен -->
<script src="//unpkg.com/alpinejs" defer></script>