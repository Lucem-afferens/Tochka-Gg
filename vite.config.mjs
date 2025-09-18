import { defineConfig } from 'vite';
import { visualizer } from 'rollup-plugin-visualizer';

export default defineConfig({
  build: {
    outDir: 'dist',
    assetsDir: 'assets',
    minify: 'terser',
    cssMinify: true,
    rollupOptions: {
      output: {
        entryFileNames: 'assets/[name]-[hash].js',
        chunkFileNames: 'assets/[name]-[hash].js',
        assetFileNames: 'assets/[name]-[hash].[ext]',
        manualChunks: {
          vendor: ['swiper'],
          styles: ['src/sass/style.scss']
        }
      }
    },
    // Критическая оптимизация
    chunkSizeWarningLimit: 1000,
    assetsInlineLimit: 4096, // Инлайним маленькие ресурсы
    terserOptions: {
      compress: {
        drop_console: true,
        drop_debugger: true,
        pure_funcs: ['console.log', 'console.info'],
        passes: 3,
        unsafe: true,
        unsafe_comps: true
      },
      mangle: {
        safari10: true,
        properties: {
          regex: /^_/
        }
      }
    },
    target: 'es2020',
    sourcemap: false,
    reportCompressedSize: false
  },
  server: {
    open: true,
    port: 3000,
  },
  // Оптимизация изображений
  assetsInclude: ['**/*.webp', '**/*.svg', '**/*.png', '**/*.jpg', '**/*.jpeg', '**/*.gif'],
  // Оптимизация CSS
  css: {
    devSourcemap: false,
    preprocessorOptions: {
      scss: {
        api: 'modern-compiler',
        silenceDeprecations: ['legacy-js-api']
      }
    }
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