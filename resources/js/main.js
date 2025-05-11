document.addEventListener("DOMContentLoaded", function() {
    const mobileMenuButton = document.querySelector('.toggle-menu');
    const mobileNav = document.querySelector('.menu-top');

    if (mobileMenuButton && mobileNav) {
        mobileMenuButton.addEventListener('click', () => {
             mobileNav.classList.toggle('hidden');
        });
    }
});
