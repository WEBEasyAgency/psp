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
                'resources/css/ui-components.css',
                'resources/css/libs.min.css',
                'resources/css/app.min.css',
                'resources/js/app.js',
                'resources/js/pages/home.js',
                'resources/js/pages/welcome.js',
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
                'resources/js/pages/product-162.js',
                'resources/js/pages/product-163.js',
                'resources/js/pages/product-164.js',
                'resources/js/pages/product-166.js',
                'resources/js/pages/product-167.js',
                'resources/js/pages/product-168.js',
                'resources/js/pages/product-169.js',
                'resources/js/pages/product-170.js',
                'resources/js/pages/product-171.js',
                'resources/js/pages/product-172.js',
                'resources/js/pages/product-173.js',
                'resources/js/pages/product-175.js',
                'resources/js/pages/cart.js',
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
        host: '127.0.0.1', // Force IPv4 instead of IPv6 to avoid VPN issues
        proxy: {
            '/backend': {
                target: 'https://psp.realeasystudio.site',
                changeOrigin: true,
                secure: false,
            },
        },
    },
});
