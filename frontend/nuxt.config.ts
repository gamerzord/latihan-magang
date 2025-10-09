import { resolve } from 'path';
import { defineNuxtConfig } from 'nuxt/config';
import vuetify, { transformAssetUrls } from 'vite-plugin-vuetify';
import basicSsl from '@vitejs/plugin-basic-ssl';

export default defineNuxtConfig({
  compatibilityDate: '2025-07-15',
  devtools: { enabled: true },

  runtimeConfig: {
    public: {
      apiBase: 'https://127.0.0.1:8000/api',
    },
  },

  typescript: { shim: false },

  devServer: {
    https: true,
  },

  vite: {
    plugins: [basicSsl()],
    server: {
      proxy: {
        '/api': {
          target: 'https://127.0.0.1:8000',
          changeOrigin: true,
          secure: false, 
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
          autoImport: true,
        }));
      });
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