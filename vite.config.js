// import { defineConfig } from 'vite';
// import laravel from 'laravel-vite-plugin';

// export default defineConfig({
//     plugins: [
//         laravel({
//             input: ['resources/css/app.css', 'resources/js/app.js'],
//             refresh: true,
//         }),
//     ],
//     server: {
//         // Gunakan host 'localhost' sebagai percobaan terakhir untuk mengatasi konflik IP
//         host: 'localhost', 
//         port: 5173,
//         strictPort: true, // Pastikan port 5173 digunakan secara eksklusif
//         hmr: {
//              // Pastikan hmr juga menggunakan host yang sama
//              host: 'localhost', 
//         }
//     }
// });

// CEK BUKA DI HP 
//  server lokal menyesuaikan laptop php artisan serve --host=192.168.11.230 --port=8000

import { defineConfig } from 'vite'
import laravel from 'laravel-vite-plugin'

export default defineConfig({
    server: {
        host: '0.0.0.0',
        port: 5173,
        strictPort: true,
        hmr: {
            host: '192.168.0.110',
        },
    },
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
})