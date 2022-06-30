<template>
  <b-form-input v-model="myValue" v-bind="$props" />
</template>

<script>
import {BFormInput} from 'bootstrap-vue'
import Slugify from 'slugify'

export default {
  name: 'InputAlias',
  props: {
    ...BFormInput.extendOptions.props,
    value: {
      type: String,
      required: true,
    },
    watch: {
      type: String,
      default: '',
    },
  },
  computed: {
    myValue: {
      get() {
        return this.value
      },
      set(newValue) {
        newValue = Slugify(newValue, {
          replacement: '-',
          remove: /[^\w\s-]+/g,
          lower: true,
          strict: true,
        })
        this.$emit('input', newValue)
      },
    },
  },
  watch: {
    watch(newValue) {
      this.myValue = newValue
    },
  },
}
</script>
