import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import basicSsl from '@vitejs/plugin-basic-ssl';

const isNgrok = process.env.VITE_USE_HTTPS === 'true';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        ...(isNgrok ? [basicSsl()] : []),
    ],
    server: {
        host: isNgrok ? '0.0.0.0' : 'localhost',
        port: 5173,
        https: isNgrok,
        hmr: {
            host: isNgrok ? 'your-ngrok-domain.ngrok-free.dev' : 'localhost',
            protocol: isNgrok ? 'wss' : 'ws',
        },
    },
});

