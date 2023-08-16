export default {
  async beforeMount() {
    try {
      const translations = []
      const languages = await this.$store.dispatch('languages')
      languages.forEach((v) => {
        const idx = this.record.translations.findIndex((i) => i.lang === v.lang)
        translations.push(idx > -1 ? {...this.record.translations[idx]} : {...this.translationFields, lang: v.lang})
      })
      this.$set(this.record, 'translations', translations)
    } catch (e) {
      console.error(e)
    }
  },
  data() {
    return {
      translationFields: {},
    }
  },
  directives: {
    langIcon: {
      update(el, {value}) {
        if (!el.classList.contains('form-group')) {
          el = el.querySelector('.form-group')
        }
        if (!el) {
          return
        }

        el.classList.forEach((cls) => {
          if (cls.startsWith('icon-')) {
            el.classList.remove(cls)
          }
        })
        el.classList.add('icon-' + value)
      },
    },
  },
}
