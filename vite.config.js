import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/views/area/index/js/index.js',
                'resources/views/fibonachi/index/js/index.js',
                'resources/views/area/create/js/index.js',
                'resources/views/home/js/index.js',
            ],
            refresh: true,
        }),
    ],
    server: {
        port: 5173,
        host: '127.0.0.1',
        proxy: {
        '/build': 'http://localhost:8000',
        },
        hmr: true,
    }
});
