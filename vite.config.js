import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue'; // 👈 Importamos el plugin de Vue

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        vue(), // 👈 Activamos el plugin de Vue
    ],
    resolve: {
        alias: {
            '@': '/resources/js',
        },
    },
    server: {
        proxy: {
            '/api': 'http://localhost:8000', // 👈 Esto redirige /api al backend de Laravel
        },
    },
});