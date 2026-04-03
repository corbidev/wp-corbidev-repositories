import { defineConfig } from 'vite'
import react from '@vitejs/plugin-react'
import path from 'path'

export default defineConfig(({ mode }) => ({

  plugins: [react()],

  root: '.',

  /**
   * ⚡ Empêche Vite de pré-bundler WP
   */
  optimizeDeps: {
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

      /**
       * 🔥 Entrées
       */
      input: {
        app: path.resolve(__dirname, 'assets/src/main.js'),
        admin: path.resolve(__dirname, 'assets/src/admin/main.js'),
        'ui-bridge': path.resolve(__dirname, 'assets/src/ui-bridge/main.tsx'),
      },

      /**
       * 🔥 Externalisation WordPress (CRITIQUE)
       */
      external: (id) => id.startsWith('@wordpress/'),

      output: {
        entryFileNames: 'assets/[name]-[hash].js',
        chunkFileNames: 'assets/[name]-[hash].js',
        assetFileNames: 'assets/[name]-[hash][extname]',

        /**
         * 🔥 Mapping global WP
         */
        globals: {
          '@wordpress/i18n': 'wp.i18n',
        },

        inlineDynamicImports: false,
      },
    },
  },

  resolve: {
    alias: {
      '@': path.resolve(__dirname, 'assets/src'),
      '@app': path.resolve(__dirname, 'assets/src'),
      '@utils': path.resolve(__dirname, 'assets/src/utils'),
      '@styles': path.resolve(__dirname, 'assets/src/styles'),
      '@core': path.resolve(__dirname, 'assets/src/core'),
      '@admin': path.resolve(__dirname, 'assets/src/admin'),
      '@core-ui': path.resolve(__dirname, 'assets/src/core-ui'),
    },
  },

}))
