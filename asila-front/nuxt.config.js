export default {
  // Global page headers: https://go.nuxtjs.dev/config-head
  head: {
    title: 'ASILA',
    htmlAttrs: {
      lang: 'en'
    },
    meta: [
      { charset: 'utf-8' },
      { name: 'viewport', content: 'width=device-width, initial-scale=1' },
      { hid: 'description', name: 'description', content: '' },
      { name: 'format-detection', content: 'telephone=no' }
    ],
    link: [
      { rel: 'icon', type: 'image/x-icon', href: '/favicon.ico' }
    ]
  },

  // Global CSS: https://go.nuxtjs.dev/config-css
  css: [
    "@/assets/css/main.css"
  ],

  // Plugins to run before rendering page: https://go.nuxtjs.dev/config-plugins
  plugins: [
    "@/plugins/rtl.js",
    { src: '@/plugins/carousel.js', mode: 'client' }
  ],

  // Auto import components: https://go.nuxtjs.dev/config-components
  components: true,

  // Modules for dev and build (recommended): https://go.nuxtjs.dev/config-modules
  buildModules: [
  ],

  // Modules: https://go.nuxtjs.dev/config-modules
  modules: [
    // https://go.nuxtjs.dev/axios
    '@nuxtjs/axios',
    '@nuxtjs/i18n',

  ],
  router: {
    middleware: ['i18n']
  },
  i18n: {
    locales: [
      { code: 'en', iso: 'en-US', file: 'en-US.js', dir: 'ltr' },
      { code: 'ar', iso: 'ar-SY', file: 'ar-SY.js', dir: 'rtl' },
      { code: 'tr', iso: 'tr-TR', file: 'tr-TR.js', dir: 'ltr' }
    ],
    strategy: "prefix_and_default",
    defaultLocale: ({ store }) => store.getters['language/getSelectedLang'],
    langDir: "locales",
    vueI18n: {
      fallbackLocale: 'en',
    },
    lazy: true,
    detectBrowserLanguage: {
      useCookie: true,
      cookieKey: 'i18n_redirected',
      alwaysRedirect: false,
      fallbackLocale: 'en'
    }
  },
  // Axios module configuration: https://go.nuxtjs.dev/config-axios
  axios: {
    proxy:true,
    // Workaround to avoid enforcing hard-coded localhost:3000: https://github.com/nuxt-community/axios-module/issues/308
    baseURL: process.env.API_BASE_URL || 'http://127.0.0.1:8000/api',
  },
  // Build Configuration: https://go.nuxtjs.dev/config-build
  build: {
  }
}
