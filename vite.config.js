import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import tailwindcss from '@tailwindcss/vite';
import { resolve } from 'path';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/product.css',
                'resources/css/libs.min.css',
                'resources/css/app.min.css',
                'resources/js/app.js',
                'resources/js/pages/home.js',
                'resources/js/pages/welcome.js',
                'resources/js/pages/order.js',
                'resources/js/pages/thanx.js',
                'resources/js/pages/product-146.js',
                'resources/js/pages/product-155.js',
                'resources/js/pages/product-156.js',
                'resources/js/pages/product-157.js',
                'resources/js/pages/product-158.js',
                'resources/js/pages/product-151.js',
                'resources/js/pages/product-154.js',
                'resources/js/pages/product-159.js',
                'resources/js/pages/product-160.js',
                'resources/js/pages/product-161.js',
            ],
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
        tailwindcss(),
    ],
    resolve: {
        alias: {
            'vue': 'vue/dist/vue.esm-bundler.js',
            '@': resolve(__dirname, 'resources/js'),
        },
    },
    server: {
        proxy: {
            '/backend': {
                target: 'https://psp.realeasystudio.site',
                changeOrigin: true,
                secure: false,
            },
        },
    },
});
