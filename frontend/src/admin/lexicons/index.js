import merge from 'deepmerge'
import vespEn from '@vesp/frontend/lexicons/en'
import vespRu from '@vesp/frontend/lexicons/ru'
import localEn from './en'
import localRu from './ru'

export default () => {
  return {
    fallbackLocale: 'en',
    messages: {
      en: merge(vespEn, localEn),
      ru: merge(vespRu, localRu),
    },
  }
}
