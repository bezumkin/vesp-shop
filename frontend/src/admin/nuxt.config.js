import {merge, union} from 'lodash'
import {Config, findEnv, loadEnv} from '@vesp/frontend'

Config.ssr = false
Config.srcDir = __dirname
Config.target = 'static'
Config.buildDir = '.nuxt/admin'
Config.build = {
  babel: {
    compact: true,
  },
}
Config.generate = {
  cache: false,
  dir: 'dist/admin',
  exclude: [/^\//],
}

const env = loadEnv(findEnv('../'))
Config.axios.baseURL = env.API_URL || '/api/'
Config.head.title = env.APP_NAME || 'Vesp Framework'
Config.publicRuntimeConfig = {
  SITE_URL: env.SITE_URL,
  PRODUCTS_PREFIX: env.PRODUCTS_PREFIX || '',
}

Config.modules = union(Config.modules, ['@vesp/frontend', '@nuxtjs/auth-next'])
Config.plugins = ['@/plugins/utils.js']

Config.router = merge(Config.router, {
  base: '/admin/',
  middleware: ['auth'],
})

// Specify vueI18n config as a function for smooth development
Config.i18n.vueI18n = '@/lexicons/index.js'
Config.i18n.defaultLocale = 'ru'
Config.i18n.locales = [
  {code: 'ru', title: 'Русский'},
  {code: 'en', title: 'English'},
]

Config.fontawesome = merge(Config.fontawesome, {
  addCss: false,
  icons: {
    solid: union(Config.fontawesome.icons.solid, [
      'faUsers',
      'faArrowLeft',
      'faSignOutAlt',
      'faImages',
      'faUpload',
      'faPowerOff',
      'faCheck',
      'faMinus',
    ]),
  },
})

Config.bootstrapVue = merge(Config.bootstrapVue, {
  componentPlugins: union(Config.bootstrapVue.componentPlugins, [
    'TabsPlugin',
    'ImagePlugin',
    'FormCheckboxPlugin',
    'FormTagsPlugin',
    'FormSpinbuttonPlugin',
    'DropdownPlugin',
    'ListGroupPlugin',
    'SpinnerPlugin',
    'BadgePlugin',
  ]),
})

Config.server = {
  host: '127.0.0.1',
  port: '4000',
}
export default Config
