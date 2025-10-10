import { defineNuxtPlugin } from 'nuxt/app'
import { createVuetify } from 'vuetify'

export default defineNuxtPlugin((nuxtApp) => {
  const vuetify = createVuetify({
    ssr: true,
    theme: {
      defaultTheme: 'light',
      themes: {
        light: {
          colors: {
            primary: '#34a983',
            secondary: '#3667a9',
            accent: '#f68a20',
          },
        },
      },
    },
  });

  nuxtApp.vueApp.use(vuetify)
});