import { defineConfig } from 'vite';
import vue from '@vitejs/plugin-vue';
import { createHtmlPlugin } from 'vite-plugin-html';
import tailwindcss from '@tailwindcss/vite';
import { resolve, extname } from 'path';
import { readFileSync, existsSync, statSync } from 'fs';

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
    {
      name: 'serve-layout-files',
      configureServer(server) {
        server.middlewares.use((req, res, next) => {
          if (req.url.startsWith('/layout/')) {
            // Удаляем query params если есть (например ?v=...)
            const urlPath = req.url.split('?')[0];
            const layoutPath = resolve(__dirname, '../layout', urlPath.replace('/layout/', ''));
            
            if (existsSync(layoutPath) && statSync(layoutPath).isFile()) {
               const ext = extname(layoutPath);
               let contentType = 'text/plain';
               if (ext === '.js') contentType = 'application/javascript';
               else if (ext === '.css') contentType = 'text/css';
               else if (ext === '.png') contentType = 'image/png';
               else if (ext === '.jpg') contentType = 'image/jpeg';
               else if (ext === '.svg') contentType = 'image/svg+xml';
               else if (ext === '.woff2') contentType = 'font/woff2';
               else if (ext === '.woff') contentType = 'font/woff';
               
               res.setHeader('Content-Type', contentType);
               res.end(readFileSync(layoutPath));
               return;
            }
          }
          next();
        });
      }
    }
  ],

  resolve: {
    alias: {
      'vue': 'vue/dist/vue.esm-bundler.js',
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
        'product-155': resolve(__dirname, 'product-155.html'),
        'sign-156': resolve(__dirname, 'sign-156.html'),
        'sign-157': resolve(__dirname, 'sign-157.html'),
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
    proxy: {
      '/backend': {
        target: 'https://psp.realeasystudio.site',
        changeOrigin: true,
        secure: false,
      },
    },
  },

  publicDir: 'public',
}));
