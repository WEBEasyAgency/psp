import { defineConfig } from 'vite';
import vue from '@vitejs/plugin-vue';
import { createHtmlPlugin } from 'vite-plugin-html';
import tailwindcss from '@tailwindcss/vite';
import { resolve } from 'path';
import { readFileSync } from 'fs';

// Функция для извлечения header/footer из существующего layout/index.html
function extractLayoutParts() {
  try {
    const html = readFileSync(resolve(__dirname, '../layout/index.html'), 'utf-8');

    // Извлекаем header (от начала до main)
    const headerMatch = html.match(/([\s\S]*?)<main/);
    const header = headerMatch ? headerMatch[1] : '';

    // Извлекаем footer (от закрытия main до конца)
    const footerMatch = html.match(/<\/main>([\s\S]*)/);
    const footer = footerMatch ? footerMatch[1] : '';

    return { header, footer };
  } catch (e) {
    console.warn('Could not extract layout parts from layout/index.html');
    return { header: '', footer: '' };
  }
}

const { header, footer } = extractLayoutParts();

export default defineConfig(({ command }) => ({
  // base только для production сборки, для dev-сервера используем корень
  base: command === 'build' ? '/dev/dist/' : '/',

  plugins: [
    vue(),
    tailwindcss(),
    createHtmlPlugin({
      minify: false,
      inject: {
        data: {
          header,
          footer,
        },
      },
    }),
  ],

  resolve: {
    alias: {
      '@': resolve(__dirname, 'src'),
      '@layout': resolve(__dirname, '../layout'),
    },
  },

  build: {
    outDir: 'dist',
    rollupOptions: {
      input: {
        main: resolve(__dirname, 'index.html'),
        product: resolve(__dirname, 'product.html'),
      },
    },
  },

  server: {
    host: '127.0.0.1',
    port: 3000,
    open: true,
    strictPort: true,
    fs: {
      // Разрешаем доступ к родительской директории (для layout/)
      allow: ['..'],
    },
  },

  publicDir: 'public',
}));
