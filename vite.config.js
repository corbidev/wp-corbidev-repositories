import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import path from 'path'

export default defineConfig(({ mode }) => ({

  plugins: [vue()],

  root: '.',

  optimizeDeps: {
    // ✅ évite le pré-bundle Vite
    exclude: ['@wordpress/i18n'],
  },

  build: {
    target: 'es2018',

    chunkSizeWarningLimit: 1000,
    outDir: 'assets/dist',
    emptyOutDir: true,
    manifest: true,
    sourcemap: mode !== 'production',
    minify: mode === 'production',

    rollupOptions: {
      input: {
        app: path.resolve(__dirname, 'assets/src/main.js'),
        admin: path.resolve(__dirname, 'assets/src/admin/main.js'),
      },

      // ✅ FIX CRITIQUE : external WP packages (robuste)
      external: (id) => id.includes('@wordpress/'),

      output: {
        // ⚠️ PAS de format iife → ES modules obligatoires

        globals: {
          '@wordpress/i18n': 'wp.i18n',
        },

        // ✅ recommandé WP
        inlineDynamicImports: false,
      }
    }
  },

  resolve: {
    alias: {
      '@': path.resolve(__dirname, 'assets/src'),
      '@app': path.resolve(__dirname, 'assets/src'),
      '@styles': path.resolve(__dirname, 'assets/src/styles'),
      '@core': path.resolve(__dirname, 'assets/src/core'),
      '@admin': path.resolve(__dirname, 'assets/src/admin'),
    }
  }

}))
