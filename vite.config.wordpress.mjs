/**
 * Vite конфигурация для WordPress темы
 * 
 * Эта конфигурация предназначена для разработки WordPress темы
 * с использованием Vite для компиляции SASS и JavaScript
 */

import { defineConfig } from 'vite';
import { resolve } from 'path';
import { writeFileSync, mkdirSync } from 'fs';

// Пути
const THEME_NAME = 'tochkagg-theme';
const WORDPRESS_THEME_PATH = resolve(__dirname, `../wp-content/themes/${THEME_NAME}`);
const LOCAL_THEME_PATH = resolve(__dirname, 'theme');
const ASSETS_PATH = LOCAL_THEME_PATH + '/assets';

// Плагин для автоматического копирования файлов в тему
const copyToThemePlugin = () => {
  return {
    name: 'copy-to-theme',
    buildStart() {
      // Создаем директории если их нет
      mkdirSync(resolve(ASSETS_PATH, 'css'), { recursive: true });
      mkdirSync(resolve(ASSETS_PATH, 'js'), { recursive: true });
    },
    writeBundle() {
      console.log('✅ Файлы скомпилированы в:', ASSETS_PATH);
    }
  };
};

export default defineConfig({
  root: resolve(__dirname),
  base: '/',
  
  build: {
    outDir: ASSETS_PATH,
    emptyOutDir: true,
    manifest: false,
    sourcemap: true,
    
    rollupOptions: {
      input: {
        main: resolve(__dirname, 'src/js/main.js'),
        style: resolve(__dirname, 'src/sass/style.scss'),
      },
      
      output: {
        entryFileNames: (chunkInfo) => {
          if (chunkInfo.name === 'main') {
            return 'js/main.js';
          }
          return 'js/[name].js';
        },
        chunkFileNames: 'js/[name]-[hash].js',
        
        assetFileNames: (assetInfo) => {
          if (!assetInfo.name) {
            return 'assets/[name]-[hash][extname]';
          }
          
          if (/\.(css)$/.test(assetInfo.name)) {
            return 'css/style.css';
          }
          
          if (/\.(png|jpe?g|svg|gif|webp)$/.test(assetInfo.name)) {
            return 'images/[name]-[hash][extname]';
          }
          
          if (/\.(woff2?|eot|ttf|otf)$/.test(assetInfo.name)) {
            return 'fonts/[name]-[hash][extname]';
          }
          
          return 'assets/[name]-[hash][extname]';
        },
      },
    },
    
    cssCodeSplit: false,
    minify: process.env.NODE_ENV === 'production' ? 'terser' : false,
    chunkSizeWarningLimit: 1000,
  },
  
  css: {
    devSourcemap: true,
    preprocessorOptions: {
      scss: {
        api: 'modern-compiler',
        silenceDeprecations: ['legacy-js-api'],
      },
    },
  },
  
  server: {
    port: 3000,
    host: true,
    open: false,
    watch: {
      usePolling: true,
      interval: 1000,
    },
    cors: true,
    fs: {
      allow: ['..']
    }
  },
  
  preview: {
    port: 4173,
    open: false,
  },
  
  optimizeDeps: {
    include: [],
  },
  
  assetsInclude: [
    '**/*.webp',
    '**/*.svg',
    '**/*.png',
    '**/*.jpg',
    '**/*.jpeg',
    '**/*.gif',
  ],
  
  plugins: [
    copyToThemePlugin(),
  ],
});
