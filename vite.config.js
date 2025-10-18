import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        tailwindcss(),
        laravel({
        input: [
            'resources/css/app.css',
            'resources/ts/app.tsx',
            'resources/js/app.js',
            'resources/js/dashboard.js', // thêm dòng này
            'resources/js/loader.js',
        ],
        refresh: true,
    })],
});

