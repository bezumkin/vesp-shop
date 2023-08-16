function translate(translations, field, lang = 'ru') {
  const data = translations.filter((i) => i.lang === lang)
  if (data.length > 0 && data[0][field] && data[0][field].length > 0) {
    return data[0][field]
  }
  return null
}

export default ({app}, inject) => {
  inject('translate', (translations, field = 'title') => {
    let text = translate(translations, field, app.i18n.locale)
    if (!text && app.i18n.locale !== app.i18n.defaultLocale) {
      text = translate(translations, field, app.i18n.defaultLocale)
    }
    if (!text) {
      console.info(`Could not find translation for ${field} in ${JSON.stringify(translations)}`)
    }
    return text
  })
}
