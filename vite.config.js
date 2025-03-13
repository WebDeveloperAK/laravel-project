import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import react from '@vitejs/plugin-react';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/js/app.jsx'], // Make sure the file exists
            refresh: true,
        }),
        react(),
    ],
    server: {
        host: '127.0.0.1', // Use 127.0.0.1 instead of [::1]
        port: 5173, // Ensure this matches the Vite server port
    },
});
