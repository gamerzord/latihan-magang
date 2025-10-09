import { resolve } from 'path';
import { defineNuxtConfig } from 'nuxt/config';
import vuetify, { transformAssetUrls } from 'vite-plugin-vuetify';

export default defineNuxtConfig({
  typescript: { shim: false },
  
  devServer: {
    https: {
      key: './key.pem',
      cert: './cert.pem',
    },
    port: 3000,
    host: 'localhost'
  },
  
  css: [
    'vuetify/styles',
    '@mdi/font/css/materialdesignicons.css',
  ],
  
  compatibilityDate: '2025-07-15',
  devtools: { enabled: false },
  
  runtimeConfig: {
    public: {
      apiBase: 'https://127.0.0.1/api',
    },
  },
  
  ssr: true, // or false if you want SPA mode
  
  vite: {
    server: {
      proxy: {
        '/api': {
          target: 'https://127.0.0.1',
          changeOrigin: true,
          secure: false, // Add this for self-signed certs
        },
      },
    },
    vue: {
      template: {
        transformAssetUrls,
      },
    },
  },
  
  modules: [
    (_options, nuxt) => {
      nuxt.hooks.hook('vite:extendConfig', (config) => {
        // @ts-expect-error
        config.plugins.push(vuetify({
          styles: { configFile: resolve(__dirname, 'assets/styles/index.scss') },
          autoImport: true,
        }));
      });
    },
    '@nuxt/eslint',
  ],
  
  build: {
    transpile: ['vuetify'],
  },
});