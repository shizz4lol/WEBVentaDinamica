
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import react from '@vitejs/plugin-react';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            // El CSS va como entrada aparte para que cargue antes que el JS (sin "parpadeo").
            input: ['resources/css/app.css', 'resources/js/app.jsx'],
            refresh: true,
        }),
        react(),
        tailwindcss(),
    ],
    server: {
        host: '0.0.0.0',
        port: 5173,
        strictPort: true,
        hmr: {
            host: 'localhost',
        },
        watch: {
            // Con el proyecto en un disco de Windows y Vite dentro de Docker, Vite no se entera
            // de los cambios en los archivos. usePolling hace que revise cada tanto.
            usePolling: true,
            interval: 300,
            ignored: ['**/storage/**', '**/vendor/**', '**/node_modules/**'],
        },
    },
});
