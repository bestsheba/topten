import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/glide.js',
                // admin
                'resources/assets/admin/css/app.scss',
                'resources/assets/admin/js/adminlte.js',
                'resources/assets/admin/js/chartjs.js',
            ],
            refresh: true,
        }),
    ],
});
