// import { defineConfig } from 'vite';
// import laravel from 'laravel-vite-plugin';
// import tailwindcss from '@tailwindcss/vite';

// export default defineConfig({
//     // server: {
//     //     host: '0.0.0.0', // Cho phép truy cập từ bên ngoài
//     //     port: 5173, // Cổng Vite
//     //     hmr: {
//     //         protocol: 'ws', // Đảm bảo WebSocket hoạt động
//     //     }
//     // },
//     plugins: [
//         laravel({
//             input: ['resources/css/app.css', 'resources/js/app.js'],
//             refresh: true,
//         }),
//         tailwindcss(),
//     ],
// });




import { defineConfig } from 'vite'
import laravel from 'laravel-vite-plugin'
import fg from 'fast-glob'

export default defineConfig({
    plugins: [
        laravel({
            input: [
                // Quét tất cả file CSS trong resources/css và subfolders
                ...fg.sync('resources/css/**/*.css'),
                // Quét tất cả file JS trong resources/js và subfolders
                ...fg.sync('resources/js/**/*.js'),
            ],
            refresh: true,
        }),
    ],
})
