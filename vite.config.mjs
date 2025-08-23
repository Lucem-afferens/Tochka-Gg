import { defineConfig } from 'vite';
import { visualizer } from 'rollup-plugin-visualizer';

export default defineConfig({
  build: {
    outDir: 'dist',
    assetsDir: 'assets',
    minify: 'terser', // Лучшая минификация
    cssMinify: true,
    rollupOptions: {
      output: {
        entryFileNames: 'assets/main.js', // ⬅️ Без хеша
        manualChunks: {
          // Разделение vendor библиотек для лучшего кеширования
          swiper: ['swiper']
        }
      }
    },
    terserOptions: {
      compress: {
        drop_console: true, // Удаляем console.log в продакшене
        drop_debugger: true,
        pure_funcs: ['console.log'], // Удаляем конкретные функции
        passes: 2 // Двойная оптимизация
      },
      mangle: {
        safari10: true // Совместимость с Safari
      }
    },
    target: 'es2015', // Поддержка старых браузеров
    sourcemap: false // Отключаем sourcemap для продакшена
  },
  server: {
    open: true,
    port: 3000,
  },
  // Оптимизация изображений
  assetsInclude: ['**/*.webp', '**/*.svg', '**/*.png', '**/*.jpg', '**/*.jpeg', '**/*.gif'],
  // Оптимизация CSS
  css: {
    devSourcemap: false, // Отключаем sourcemap в dev для быстроты
  },
  // Bundle analyzer
  plugins: [
    visualizer({
      filename: 'dist/stats.html',
      open: true,
      gzipSize: true,
      brotliSize: true
    })
  ]
});