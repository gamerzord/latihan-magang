import { resolve } from 'path'
import { defineNuxtConfig } from 'nuxt/config'
import vuetify, { transformAssetUrls } from 'vite-plugin-vuetify';

export default defineNuxtConfig({
  compatibilityDate: '2025-07-15',
  devtools: { enabled: true },

  runtimeConfig: {
    public: {
      apiBase: 'http://127.0.0.1:8000/api',
    },
  },

  typescript: { shim: false },

  vite: {
    server: {
      proxy: {
        '/api': {
        target: 'http://127.0.0.1:8000',
        changeOrigin: true,
      },
    },
  },
    vue: {
      template: {
        transformAssetUrls,
      },
    },
    ssr: { noExternal: ['vuetify'] },
  },

  pages: true,

  modules: [
    (_options, nuxt) => {
      nuxt.hooks.hook('vite:extendConfig', (config) => {
        // @ts-expect-error
        config.plugins.push(vuetify({ 
          styles: {configFile: resolve(__dirname, 'assets/styles/index.scss')},
          autoImport: true }))
      })
    },
    '@nuxt/eslint',
  ],

  css: [
    'vuetify/styles',
    '@mdi/font/css/materialdesignicons.css',
  ],

  build: {
    transpile: ['vuetify'],
  },
});