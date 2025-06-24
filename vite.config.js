import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/sass/app.scss',
                'resources/css/carousel.css',
                'resources/css/about.css',
                'resources/css/sidebars.css',
                'resources/js/app.js',
                'resources/js/admin.js',
                'resources/js/site.js',
            ],
            refresh: true,
        }),
    ],
});
