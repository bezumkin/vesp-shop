import localEn from './en'
import localRu from './ru'

export default () => {
  return {
    fallbackLocale: 'ru',
    messages: {
      en: localEn,
      ru: localRu,
    },
  }
}
