<header class="bg-black text-white py-4 px-6 relative">
    <div class="container mx-auto flex justify-between items-center">
        <h1 class="text-xl font-bold">HomeWork</h1>
        <nav class="menu-top hidden md:block absolute md:static top-18 left-0 right-0 bg-black border-t-1 border-white md:border-t-0">
            <ul class="flex  space-x-4 flex-col md:flex-row">

                <li><a href="/" class="hover:text-blue-200 transition-colors p-5 md:p-0 block">Главная</a></li>


                @if (Route::has('profile'))
                    <li><a href="{{ route('profile') }}" class="hover:text-blue-200 transition-colors p-5 md:p-0 block">Личный кабинет</a></li>
                @endif


                @if (Route::has('registration'))
                <li><a href="{{ route('registration') }}" class="hover:text-blue-200 transition-colors p-5 md:p-0 block">Регистрация</a></li>
                @endif

                @if (Route::has('about'))
                    <li><a href="{{ route('about') }}" class="hover:text-blue-200 transition-colors p-5 md:p-0 block">О компании</a></li>
                @endif
            </ul>
        </nav>
        <div class="md:hidden">
            <button class="text-white hover:bg-blue-600 px-4 py-2 rounded-md toggle-menu">
                Меню
            </button>
        </div>
    </div>
</header>
