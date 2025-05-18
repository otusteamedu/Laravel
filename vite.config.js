import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    server: {
        host: true,
        port: 5175,
    },
    plugins: [
        laravel({
            input: ['resources/css/common.scss', 'resources/css/breeze/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
});
