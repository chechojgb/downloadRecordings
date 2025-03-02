import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import flowbitePlugin from 'flowbite/plugin'; 

export default defineConfig({
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
        "./node_modules/flowbite/**/*.js"
    ],
    theme: {
        extend: {},
    },
    plugins: [
        flowbitePlugin, 
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
});
