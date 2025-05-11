<footer class="bg-gray-800 text-white py-4 px-6">
    <div class="container mx-auto text-center">
        <div class="flex justify-center mb-4">
            <ul class="flex md:space-x-4 flex-col md:flex-row">

                <li><a href="/" class="hover:text-gray-300 transition-colors text-sm p-2 md:p-0 block">Главная</a></li>

                @if (Route::has('profile'))
                <li><a href="{{ route('profile') }}" class="hover:text-gray-300 transition-colors text-sm p-2 md:p-0 block">Личный кабинет</a></li>
                @endif

                @if (Route::has('registration'))
                <li><a href="{{ route('registration') }}" class="hover:text-gray-300 transition-colors text-sm p-2 md:p-0 block">Регистрация</a></li>
                @endif

                @if (Route::has('about'))
                <li><a href="{{ route('about') }}" class="hover:text-gray-300 transition-colors text-sm p-2 md:p-0 block">О компании</a></li>
                @endif
            </ul>
        </div>
        <p>&copy; 2025. Все права защищены.</p>
        <p>
            <a href="#" class="hover:text-gray-300 transition-colors text-sm">Политика конфиденциальности</a>
        </p>
    </div>
</footer>
<script>
    const mobileMenuButton = document.querySelector('.md\\:hidden button');
    const mobileNav = document.querySelector('.md\\:hidden nav');

    if (mobileMenuButton && mobileNav) {
        mobileMenuButton.addEventListener('click', () => {
            // mobileNav.classList.toggle('hidden');  // Simple toggle, for demonstration
            alert("Mobile menu toggling would be implemented here with JavaScript, likely involving adding/removing a 'hidden' class or similar, to show/hide a more complex menu.");
        });
    }
</script>
