import { defineConfig } from 'vite';
import tailwindcss from '@tailwindcss/vite'

export default defineConfig(() => {
    return {
        build: {
            outDir: 'public/build',
            copyPublicDir: false,
            manifest: 'manifest.json',
            rollupOptions: {
                input: [
                    'resources/css/app.css',
                    'resources/js/app.js',
                ]
            }
        },
        server: {
            host: true,
            port: 5173,
            strictPort: true,
            allowedHosts: true,
            cors: true,
        },
        plugins: [
            tailwindcss(),
        ],
        cacheDir: '.cache/vite',
    }
});
