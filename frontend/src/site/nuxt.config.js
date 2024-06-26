import {Config, findEnv, loadEnv} from '@vesp/frontend'

Config.ssr = true
Config.srcDir = __dirname
Config.target = 'server'
Config.buildDir = '.nuxt/site'
Config.build = {
  babel: {
    compact: true,
  },
  transpile: ['defu'],
}
Config.generate = {
  cache: false,
  dir: 'dist/site',
  exclude: [/^\//],
}

const env = loadEnv(findEnv('../'))
Config.head.title = env.APP_NAME || 'Vesp Framework'
Config.head.link = [
  {rel: 'icon', type: 'image/x-icon', href: '/favicons/favicon.ico'},
  {rel: 'icon', type: 'image/png', sizes: '32x32', href: '/favicons/favicon-32x32.png'},
  {rel: 'icon', type: 'image/png', sizes: '16x16', href: '/favicons/favicon-16x16.png'},
  {rel: 'apple-touch-icon', sizes: '180x180', href: '/favicons/apple-touch-icon.png'},
  {rel: 'manifest', href: '/favicons/site.webmanifest'},
]
Config.publicRuntimeConfig = {
  PRODUCTS_PREFIX: env.PRODUCTS_PREFIX || '',
  PAYMENT_SERVICES: env.PAYMENT_SERVICES || '',
}

Config.modules = [...Config.modules, '@vesp/frontend', '@nuxtjs/auth-next']
Config.plugins = ['../admin/plugins/utils.js', '~/plugins/utils.js']

Config.i18n.vueI18n = '~/lexicons/index.js'
Config.i18n.defaultLocale = 'ru'
Config.i18n.locales = [
  {code: 'ru', title: 'Русский'},
  {code: 'en', title: 'English'},
]

Config.pwa = {
  icon: {
    fileName: 'favicons/android-chrome-512x512.png',
  },
}

Config.fontawesome = {
  addCss: false,
  component: 'fa',
  icons: {
    solid: [
      'faHome',
      'faCartShopping',
      'faMinus',
      'faPlus',
      'faRightToBracket',
      'faRightFromBracket',
      'faUser',
      'faEye',
      'faKey',
      'faCaretDown',
      'faSync',
      'faTimes',
      'faCheck',
    ],
  },
}

Config.bootstrapVue.componentPlugins = [
  'LayoutPlugin',
  'NavbarPlugin',
  'TablePlugin',
  'TabsPlugin',
  'ImagePlugin',
  'AlertPlugin',
  'LinkPlugin',
  'PaginationPlugin',
  'OverlayPlugin',
  'ButtonPlugin',
  'ButtonGroupPlugin',
  'CarouselPlugin',
  'BreadcrumbPlugin',
  'ModalPlugin',
  'FormPlugin',
  'FormGroupPlugin',
  'FormInputPlugin',
  'FormTextarea',
  'FormCheckboxPlugin',
  'FormSelectPlugin',
  'InputGroupPlugin',
  'FormRadioPlugin',
  'SpinnerPlugin',
  'BadgePlugin',
]

Config.server = {
  host: '127.0.0.1',
  port: '4100',
}

export default Config
