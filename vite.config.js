import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/js/map.show.ts',
                'resources/js/map.edit.ts',
                'resources/css/mapping.css',
                'resources/js/app.js',
                'resources/js/home.js'
            ],
            refresh: true,
        }),
    ],
    resolve: {
        alias: {
            '@': '/resources/js',
        },
    },
});
