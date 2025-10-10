import { defineNuxtConfig } from 'nuxt/config'
import vuetify, { transformAssetUrls } from 'vite-plugin-vuetify'

export default defineNuxtConfig({
  typescript: { shim: false },
  pages: true,

  devServer: {
    https: {
      key: './localhost-key.pem',
      cert: './localhost.pem',
    },
    port: 3000,
    host: 'localhost',
  },

  css: [
    'vuetify/styles',
    '@mdi/font/css/materialdesignicons.css',
    'assets/styles/index.scss'
  ],

  compatibilityDate: '2025-07-15',
  devtools: { enabled: false },

  runtimeConfig: {
    public: {
      apiBase: 'https://localhost/api',
    },
  },

  vite: {
    server: {
      hmr: {
        host: 'localhost',
        port:3000,
        protocol: 'wss'
      },
      proxy: {
        '/api': {
          target: 'https://localhost',
          changeOrigin: true,
          secure: false,
        },
        '/sanctum': {
          target: 'https://localhost',
          changeOrigin: true,
          secure: false,
        },
      },
    },
    ssr: {
      noExternal: ['vuetify'],
    },
    vue: {
      template: {
        transformAssetUrls,
      },
    },
  },

  modules: [
    '@nuxt/eslint',
    async (_options, nuxt) => {
      nuxt.hooks.hook('vite:extendConfig', (config) => {
        // @ts-expect-error
        config.plugins.push(
          vuetify({
            autoImport: true,
          })
        )
      })
    },
  ],

  build: {
    transpile: ['vuetify'],
  },
})