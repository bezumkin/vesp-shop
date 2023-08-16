<template>
  <b-tabs v-model="tab" :pills="pills" :small="small" :align="align" :active-nav-item-class="activeClass">
    <b-tab v-for="(language, idx) in languages" :key="idx">
      <template #title>
        <div class="d-flex align-items-center">
          <b-img :src="require('~/assets/images/icon-lang-' + language.lang + '.svg')" width="20" height="20" />
          <div class="ml-2">{{ language.title }}</div>
        </div>
      </template>
    </b-tab>
  </b-tabs>
</template>

<script>
export default {
  name: 'LangTabs',
  props: {
    value: {
      type: String,
      default: 'en',
    },
    align: {
      type: String,
      default: 'center',
    },
    small: {
      type: Boolean,
      default: true,
    },
    pills: {
      type: Boolean,
      default: true,
    },
    activeClass: {
      type: [Array, Object, String],
      default() {
        return 'bg-light text-body'
      },
    },
  },
  data() {
    let tab = 0
    const languages = this.$store.state.languages
    if (this.value && languages.length) {
      tab = languages.findIndex((i) => i.lang === this.value)
    }
    return {tab}
  },
  computed: {
    myValue: {
      get() {
        return this.value
      },
      set(newValue) {
        this.$emit('input', newValue)
      },
    },
    languages() {
      return this.$store.state.languages
    },
  },
  watch: {
    tab(newValue) {
      if (this.languages[newValue]) {
        this.myValue = this.languages[newValue].lang
      }
    },
  },
}
</script>
