import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import path from 'path'

export default defineConfig(({ mode }) => ({
  plugins: [vue()],

  root: '.',

  build: {
    outDir: 'assets/dist',
    emptyOutDir: true,
    manifest: true,
    sourcemap: true,
    minify: mode === 'production',

    rollupOptions: {
      input: {
        app: path.resolve(__dirname, 'assets/src/main.js'),
        admin: path.resolve(__dirname, 'assets/src/admin/main.js'),
      }
    }
  },

  resolve: {
    alias: {
      '@': path.resolve(__dirname, 'assets/src'),
      '@app': path.resolve(__dirname, 'assets/src'),
      '@styles': path.resolve(__dirname, 'assets/src/styles'),
      '@core': path.resolve(__dirname, 'assets/src/core'),
    }
  }
}))
