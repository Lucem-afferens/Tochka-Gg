import { defineConfig } from 'vite';
import legacy from '@vitejs/plugin-legacy'; // для поддержки старых браузеров (опционально)

export default defineConfig({
  plugins: [
    legacy({
      targets: ['defaults', 'not IE 11']
    }),
    // vue(), // или react()
  ],
  build: {
    outDir: 'dist',
    assetsDir: 'assets',
    rollupOptions: {
      output: {
        entryFileNames: 'assets/main.js', // ⬅️ Без хеша
      }
    }
  },
  server: {
    open: true,
    port: 3000,
  }
});